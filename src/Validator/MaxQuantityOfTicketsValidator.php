<?php

namespace App\Validator;

use App\Entity\Booking;
use App\Repository\BookingRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class MaxQuantityOfTicketsValidator extends ConstraintValidator
{
    const LIMIT = 1000;

    /**
     * @var BookingRepository
     */
    private $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function validate($booking, Constraint $constraint)
    {
        /** @var Booking $booking */
        /* @var $constraint \App\Validator\MaxQuantityOfTickets */
        $nbReservedTickets =  $this->bookingRepository->countTicketsPerDate($booking->getVisitDate());

        if ($nbReservedTickets + $booking->getQuantity() > self::LIMIT){
                $this->context->buildViolation($constraint->getMessage())
                    ->setParameter('NBTICKET', self::LIMIT - $nbReservedTickets)
                    ->atPath('quantity')
                    ->addViolation();
        }

    }

}
