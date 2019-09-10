<?php

namespace App\Controller;

use App\Form\BookingType;
use App\Form\FillTicketsType;
use App\Manager\BookingManager;
use App\Services\PriceCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;


class BookingController extends AbstractController
{
    /**
     * @Route("/", name="booking")
     * @Route("/", name="home")
     * @param Request $request
     * @param BookingManager $bookingManager
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
     * @param BookingManager $bookingManager
     * @param PriceCalculator $priceCalculator
     * @return Response
     */
    public function fillTicket(Request $request, BookingManager $bookingManager, PriceCalculator $priceCalculator)
    {

        $booking = $bookingManager->getCurrentBooking();

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
     * @param BookingManager $bookingManager
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function checkout(BookingManager $bookingManager, Request $request)
    {

        $booking = $bookingManager->getCurrentBooking();

        if ($request->isMethod('POST')) {

            if ($bookingManager->doPayment($booking)) {
                return $this->redirectToRoute('step4');
            } else {
                $this->addFlash('danger', 'Problème de commnication avec notre système de paiement, Merci de ré-essayer dans quelques minutes!');
            }

        }

        return $this->render('booking/checkout.html.twig', [
            'booking' => $booking
        ]);
    }


    /**
     * @Route("/confirmation", name="step4")
     * @return Response
     */
    public function confirmation(BookingManager $bookingManager)
    {
        $booking = $bookingManager->getCurrentBooking();

        $bookingManager->removeCurrentBooking();


        return $this->render('booking/confirmation.html.twig', [
            'booking' => $booking
        ]);
    }


    /**
     * @Route("/termsOfSales", name="termsOfSales")
     */
    public function termsOfSales()
    {
        return $this->render('terms of sales & legal notice/privacyPolicy.html.twig');
    }

    /**
     * @Route("/legalNotice", name="legalNotice")
     */
    public function legalNotice()
    {
        return $this->render('terms of sales & legal notice/legalNotice.html.twig');
    }

}
