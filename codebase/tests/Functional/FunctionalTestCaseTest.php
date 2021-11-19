<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class FunctionalTestCaseTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $client->request('GET', '/demo');
        $jsonResponse = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/DemoController.php',
        ], $jsonResponse);

    }
}
