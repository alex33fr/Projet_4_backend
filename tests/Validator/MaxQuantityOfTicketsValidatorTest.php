<?php


namespace App\Tests\Validator;


use App\Entity\Booking;
use App\Repository\BookingRepository;
use App\Validator\MaxQuantityOfTickets;
use App\Validator\MaxQuantityOfTicketsValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class MaxQuantityOfTicketsValidatorTest extends  ConstraintValidatorTestCase
{

    protected function createValidator()
    {

        /** @var BookingRepository $bookingRepository */
        $bookingRepository = $this->getMockBuilder(BookingRepository::class)->disableOriginalConstructor()->getMock();

        $bookingRepository->method('countTicketsPerDate')->willReturn(990);

       return new MaxQuantityOfTicketsValidator($bookingRepository);
    }


    public function testValid(){
        $booking = new Booking();
        $booking->setVisitDate(new \DateTime());

        $booking->setQuantity(9);

        $this->validator->validate($booking, new MaxQuantityOfTickets());
        $this->assertNoViolation();
    }


    public function testInvalid(){
        $booking = new Booking();
        $booking->setVisitDate(new \DateTime());

        $booking->setQuantity(11);

        $constraint = new MaxQuantityOfTickets();
        $this->validator->validate($booking, $constraint);

        $this->buildViolation($constraint->getMessage())
            ->setParameter('NBTICKET', 10)
            ->atPath('property.path.quantity')
            ->assertRaised();
    }
}