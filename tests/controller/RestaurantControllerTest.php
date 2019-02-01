<?php
// tests/Controller/PostControllerTest.php
namespace App\tests\controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class RestaurantControllerTest extends WebTestCase
{
    public function testHome()
    {
        $clientD = static::createClient();
        $clientD->request('GET', '/');

        static::assertEquals(200, $clientD->getResponse()->getStatusCode());
        return;
    }

    // this test a not exiting route
    public function testNotexitingroute()
    {
        $client = static::createClient();
        $client->request('GET', '/not_found_root');

        static::assertEquals(404, $client->getResponse()->getStatusCode());
        return;
    }
}