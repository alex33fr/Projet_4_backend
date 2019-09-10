<?php


namespace App\Manager;


use App\Entity\Booking;
use App\Entity\Ticket;
use App\Services\Mailer;
use App\Services\Payment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class BookingManager
{
    const SESSION_ID = 'booking';
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var Payment
     */
    private $payment;
    /**
     * @var Mailer
     */
    private $mailer;
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(SessionInterface $session, Payment $payment, Mailer $mailer, EntityManagerInterface $em)
    {
        $this->session = $session;
        $this->payment = $payment;
        $this->mailer = $mailer;
        $this->em = $em;
    }


    public function initNewBooking()
    {
        $booking = new Booking();
        $this->session->set(self::SESSION_ID, $booking);

        return $booking;

    }

    public function generateEmptyTickets(Booking $booking)
    {
        $nbTicket = $booking->getQuantity();

        for ($i = 0; $i < $nbTicket; $i++) {
            $booking->addTicket(new Ticket());
        }
    }

    /**
     * @return Booking
     *
     */
    public function getCurrentBooking()
    {
        $booking = $this->session->get(self::SESSION_ID);

        if (!$booking instanceof Booking) {
            throw new NotFoundHttpException();
        }

        return $booking;
    }

    public function doPayment(Booking $booking)
    {
        if ($paymentDetails = $this->payment->charge($booking->getPrice() * 100, "Réservation billeterie Louvre")) {

            $booking->setEmail($paymentDetails['email']);
            $booking->setRefStripe($paymentDetails['ref']);
            $booking->setBuyDate(new \DateTime());
            $this->mailer->sendBookingConfirmation($booking);


            // enregistrer en BDD
            $this->em->persist($booking);
            $this->em->flush();

            return true;
        } else {
            return false;
        }
    }

    public function removeCurrentBooking()
    {
        $this->session->remove(self::SESSION_ID);
    }
}