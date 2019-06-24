<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Ticket;
use App\Form\BookingType;
use App\Form\FillTicketsType;
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
     * @Route("/booking", name="booking")
     * @param Request $request
     * @param SessionInterface $session
     * @return RedirectResponse|Response
     */
    public function index(Request $request, SessionInterface $session)
    {
        $booking = new Booking();


        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var Booking $booking */
            $booking = $form->getData();

            $nbTicket = $booking->getQuantity();


            for ($i=0; $i < $nbTicket; $i++){
                $booking->addTicket(new Ticket());
            }

            $session->set('booking', $booking);

            return $this->redirectToRoute('step2');
        }

        return $this->render('booking/index.html.twig',[
           'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/step2", name="step2")
     * @param Request $request
     * @param SessionInterface $session
     * @param PriceCalculator $priceCalculator
     * @return Response
     */
    public function fillTicket(Request $request){

        return $this->render('booking/fillTicket.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]);


    }
}
