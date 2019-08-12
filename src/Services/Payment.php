<?php


namespace App\Services;


use Stripe\Error\Card;
use Symfony\Component\HttpFoundation\RequestStack;

class Payment
{


    private $token;

    public function __construct($stripePrivateKey, RequestStack $requestStack)
    {
        \Stripe\Stripe::setApiKey($stripePrivateKey);
        $this->token = $requestStack->getCurrentRequest()->request->get('stripeToken');
    }


    public function charge(int $price, string $description)
    {
        try {
            $charge = \Stripe\Charge::create(array(
                "amount" => $price,
                "currency" => "eur",
                "source" => $this->token,
                "description" => $description
            ));

            return ['ref' => $charge['id'], 'email' => $charge['billing_details']['name']];
        } catch (Card $e) {
            return false;
        }
    }
}