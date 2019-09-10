<?php


namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ApplicationAvailabilityFunctionalTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url,$expectedStatus)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertEquals($expectedStatus,$client->getResponse()->getStatusCode());
    }

    public function urlProvider()
    {
        yield ['/',Response::HTTP_OK];
        yield ['/step2', Response::HTTP_NOT_FOUND];
        yield ['/checkout', Response::HTTP_NOT_FOUND];
        yield ['/confirmation', Response::HTTP_NOT_FOUND];
    }
}