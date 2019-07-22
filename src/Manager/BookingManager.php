<?php


namespace App\Manager;


use App\Entity\Booking;
use App\Entity\Ticket;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookingManager
{
    const SESSION_ID = 'booking';
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }


    public function initNewBooking()
    {
        $booking =  new Booking();
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
        $booking =  $this->session->get(self::SESSION_ID);

        if(!$booking instanceof  Booking){
            throw new NotFoundHttpException();
        }

        return $booking;
    }
}