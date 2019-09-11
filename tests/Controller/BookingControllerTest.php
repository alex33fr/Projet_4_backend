<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookingControllerTest extends WebTestCase
{

    public function testOrderTicket()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('CONTINUER')->form();
        $form['booking[visitDate]'] = '2019-10-21';
        $form['booking[durationType]'] = 1;
        $form['booking[quantity]'] = 1;
        $form['booking[email]'] = 'a@a.a';
        $client->submit($form);

        $crawler = $client->followRedirect();


        $this->assertSame('Formulaire de(s) participant(s)',$crawler->filter('h2')->text());

    }
}
