<?php

namespace App\Tests\Services;

use App\Entity\Booking;
use App\Entity\Ticket;
use App\Services\PriceCalculator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PriceCalculatorTest extends WebTestCase
{

    /**
     * @dataProvider  computePriceProvider
     *
     * @param $duration
     * @param $birthdate
     * @param $specialOffer
     * @param $expected
     * @throws \Exception
     */
    public function testComputePrice($duration, $birthdate, $specialOffer, $expected)
    {
        $booking = new Booking($duration);
        $booking->setVisitDate(new \DateTime('2020-01-01'));
        $booking->setDurationType($duration);

        $ticket = new Ticket();
        $ticket->setBirthDate(new \DateTime($birthdate));
        $ticket->setSpecialOffer($specialOffer);

        $booking->addTicket($ticket);


        $priceCalculator = new PriceCalculator();
        $priceCalculator->computePrice($booking);

        $this->assertEquals($expected,$booking->getPrice());


    }

    public function computePriceProvider(){

        /*Price once a child is < 4 years full day 0€*/
        yield [Booking::TYPE_DAY, '2019-01-01', false, 0];
        /*Price once a child is < 4 years full day "reduce" 0€*/
        yield [Booking::TYPE_DAY, '2019-01-01', true, 0];
        /*Price once a child is < 4 years half day 0€*/
        yield [Booking::TYPE_HALF_DAY, '2019-01-01', false, 0];
        /*Price once a child is < 4 years half day "reduce" 0€*/
        yield [Booking::TYPE_HALF_DAY, '2019-01-01', true, 0];


        /*Price once a child is 4 - 12 years full day 8€*/
        yield [Booking::TYPE_DAY, '2014-01-01', false, 8];
        /*Price once a child is 4 - 12 years full day "reduce" 8€*/
        yield [Booking::TYPE_DAY, '2014-01-01', true, 8];
        /*Price once a child is 4 - 12 years half day 4€*/
        yield [Booking::TYPE_HALF_DAY, '2014-01-01', false, 4];
        /*Price once a child is 4 - 12 years half day "reduce" 4€*/
        yield [Booking::TYPE_HALF_DAY, '2014-01-01', true, 4];


        /*Price normal full day 16€*/
        yield [Booking::TYPE_DAY, '1980-01-01', false, 16];
        /*Price normal full day "reduce" 10€*/
        yield [Booking::TYPE_DAY, '1980-01-01', true, 10];
        /*Price normal half day 8€*/
        yield [Booking::TYPE_HALF_DAY, '1980-01-01', false, 8];
        /*Price normal half day "reduce" 5€*/
        yield [Booking::TYPE_HALF_DAY, '1980-01-01', true, 5];


        /*Price senior >60 years full day 12€*/
        yield [Booking::TYPE_DAY, '1955-01-01', false, 12];
        /*Price senior >60 years full day "reduce" 10€*/
        yield [Booking::TYPE_DAY, '1955-01-01', true, 10];
        /*Price senior >60 years half day 6€*/
        yield [Booking::TYPE_HALF_DAY, '1955-01-01', false, 6];
        /*Price senior >60 years half day "reduce" 5€*/
        yield [Booking::TYPE_HALF_DAY, '1955-01-01', true, 5];

    }
}