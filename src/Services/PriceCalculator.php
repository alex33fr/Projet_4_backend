<?php


namespace App\Services;


use App\Entity\Booking;


class PriceCalculator
{


    const PRICE_REDUCE = 10;
    const PRICE_BABY = 0;
    const PRICE_CHILD = 8;
    const PRICE_NORMAL = 16;
    const PRICE_SENIOR = 12;

    const AGE_CHILD = 4;
    const AGE_ADULT = 12;
    const AGE_SENIOR = 60;
    const DURATION_HALF_DAY_COEFF = 0.5;

    public function computePrice(Booking $booking)
    {
        $total = 0;

        foreach ($booking->getTickets() as $ticket) {
            dump($ticket);
            $age = $booking->getVisitDate()->diff($ticket->getBirthDate())->y;

            //selon l'age trouver le bon prix $price
            if ($age < self::AGE_CHILD) {
                $price = self::PRICE_BABY;
            } elseif ($age < self::AGE_ADULT) {
                $price = self::PRICE_CHILD;
            } elseif ($age < self::AGE_SENIOR) {
                $price = self::PRICE_NORMAL;
            } else {
                $price = self::PRICE_SENIOR;
            }


            //si le tarif réduit est coché, $price = 10
            // (attention à ne pas faire payer plus cher de $price initial)
            if ($ticket->getSpecialOffer() && $price >= self::PRICE_REDUCE) {
                $price = self::PRICE_REDUCE;
            }


            //appliquer le coefficient de durée si demi-journee -> demi-tarif
            if ($booking->getDurationType() == Booking::TYPE_HALF_DAY) {
                $price *= self::DURATION_HALF_DAY_COEFF;
            }

            $ticket->setPrice($price);
dump($ticket);
            $total += $price;
        }
        $booking->setPrice($total);
        return $total;
    }
}