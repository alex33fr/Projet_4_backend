<?php


namespace App\Services;


use App\Entity\Booking;
use Twig\Environment;

class Mailer
{
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * Mailer constructor.
     * @param Environment $twig
     * @param \Swift_Mailer $mailer
     */
    public function __construct(Environment $twig, \Swift_Mailer $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }


    /**
     * @param Booking $booking
     * @return int
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function sendBookingConfirmation(Booking $booking)
    {
        $message = (new \Swift_Message('Musée de Louvre'))
            ->setSubject('Confirmation de payement')
            ->setFrom('reservation@lelouvre.com')
            ->setTo($booking->getEmail())
            ->setBody($this->twig->render("mail/confirmation.html.twig", [
                'booking' => $booking
            ]));


        return $this->mailer->send($message);
    }

}