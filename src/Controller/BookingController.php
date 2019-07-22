<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Ticket;
use App\Form\BookingType;
use App\Form\FillTicketsType;
use App\Manager\BookingManager;
use App\Services\PriceCalculator;
use Stripe\Error\Card;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class BookingController extends AbstractController
{
    /**
     * @Route("/booking", name="booking")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function index(Request $request, BookingManager $bookingManager)
    {
        $booking = $bookingManager->initNewBooking();


        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $bookingManager->generateEmptyTickets($booking);
            return $this->redirectToRoute('step2');
        }

        return $this->render('booking/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/step2", name="step2")
     * @param Request $request
     * @param PriceCalculator $priceCalculator
     * @return Response
     */
    public function fillTicket(Request $request, BookingManager $bookingManager, PriceCalculator $priceCalculator)
    {

        $booking =$bookingManager->getCurrentBooking();

        $form = $this->createForm(FillTicketsType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $priceCalculator->computePrice($booking);
            return $this->redirectToRoute('step3');

        }


        return $this->render('booking/fillTicket.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);


    }

    /**
     * @Route("/checkout", name="step3")
     */
    public function checkout(BookingManager $bookingManager, Request $request, \Swift_Mailer $mailer)
    {

        $booking = $bookingManager->getCurrentBooking();
        if ($request->isMethod('POST')) {
            $token = $request->request->get('stripeToken');
            \Stripe\Stripe::setApiKey($this->getParameter('stripe_private_key'));
            try {
                $charge = \Stripe\Charge::create(array(
                    "amount" => $booking->getPrice() * 100,
                    "currency" => "eur",
                    "source" => $token,
                    "description" => "Réservation billeterie Louvre"
                ));


                $booking->setEmail($charge['billing_details']['name']);
                $booking->setRefStripe($charge['id']);
                $booking->setBuyDate(new \DateTime());


                //envoyer mail de confirmation
                //TODO remettre au propre le contenu du message
                $message = (new \Swift_Message('Musée de Louvre'))
                    ->setSubject('Confirmation de payement')
                    ->setFrom('reservation@lelouvre.com')
                    ->setTo($booking->getEmail())
                    ->setBody("TEXT");


                $mailer->send($message);



                // enregistrer en BDD
                 $entityManager = $this->getDoctrine()->getManager();
                 $entityManager->persist($booking);
                 $entityManager->flush();


                return $this->redirectToRoute('step4');
            } catch (Card $e) {
                $this->addFlash('danger', 'Problème de commnication avec notre système de paiement, Merci de ré-essayer dans quelques minutes!');
            }

        }

        return $this->render('booking/checkout.html.twig', [
            'booking' => $booking
        ]);
    }


    /**
     * @Route("/confirmation", name="step4")
     */
    public function confirmation(SessionInterface $session)
    {
        $booking = $session->get('booking');

        // TODO penser à bien remettre le remove session
        //$session->remove('booking');

        return $this->render('booking/confirmation.html.twig', [
            'booking' => $booking
        ]);
    }

}
