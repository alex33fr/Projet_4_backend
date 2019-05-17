<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Booking;
use App\Form\BookingType;

class BookingController extends AbstractController
{
    /**
     * @Route("/booking", name="booking")
     */
    public function index(Request $request)
    {
        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
// enregistrer $booking en session
            return $this->redirectToRoute('step2');
        }


        return $this->render('booking/index.html.twig',[
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/step2", name="step2")
     * @param Request $request
     *
     */
    public function fillTicket(Request $request){

        //recuperer le booking depuis la session
        return new Response("Etape 2");
    }
}
