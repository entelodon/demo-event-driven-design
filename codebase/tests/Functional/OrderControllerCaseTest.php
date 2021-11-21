<?php

namespace App\Tests\Functional;

use App\Entity\Product;
use App\Entity\ProductType;
use App\Entity\PromotionalCode;
use App\Repository\ProductTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class OrderControllerCaseTest extends WebTestCase
{
    private Application $application;
    private KernelBrowser $client;
    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->application = new Application($this->client->getKernel());
        $this->application->setAutoExit(false);
        parent::setUp();
    }

    public function testNotAllowedMethod(): void
    {
        $this->client->catchExceptions(false);
        $this->expectException(MethodNotAllowedHttpException::class);
        $this->client->request('GET', '/order');
    }

    private function runCommand(string $command) {
        $this->application->run(new StringInput(sprintf('%s --quiet', $command)));
    }
}
