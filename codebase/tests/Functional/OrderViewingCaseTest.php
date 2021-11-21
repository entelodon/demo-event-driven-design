<?php

namespace App\Tests\Functional;

use App\Entity\Order;
use App\Entity\Product;
use App\Entity\ProductType;
use App\Repository\ProductTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\StringInput;

class OrderViewingCaseTest extends WebTestCase
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

    /**
     * @dataProvider showOrderProvider
     */
    public function testShowOrder(array $orderData, int $orderId, array $expected, int $code): void
    {
        $this->createDb();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        /** @var ProductTypeRepository $productTypeRepository */
        $productTypeRepository = $this->getContainer()->get(ProductTypeRepository::class);
        $order = new Order();
        foreach ($orderData['products'] as $product) {
            $productEntity = new Product();
            $productEntity->setType($this->createOrGetProductType($product['type'], $productTypeRepository, $entityManager));
            $productEntity->setName($product['name']);
            $productEntity->setPrice($product['price']);
            $entityManager->persist($productEntity);
            $entityManager->flush();
            $order->addProduct($productEntity);
        }
        $order->setPrice(3000);
        $order->setDiscountPrice(0);
        $order->setFinalPrice(3000);
        $entityManager->persist($order);
        $entityManager->flush();

        $this->client->jsonRequest('GET', '/order/' . $orderId);
        $this->assertEquals($code, $this->client->getResponse()->getStatusCode());
        $jsonResponse = $this->parseJsonResponse($this->client->getResponse()->getContent());
        $this->assertSame($expected, $jsonResponse);
    }

    public function showOrderProvider(): array
    {
        return [
            [
                [
                    'products' => [
                        [
                            'type' => 'book',
                            'name' => 'Quantum Mechanics',
                            'price' => 3000,
                        ],
                    ],
                ],
                1,
                [
                    'message' => [
                        'productNames' => [
                            'Quantum Mechanics',
                        ],
                        'promotionalCodes' => [],
                        'price' => 3000,
                        'discountPrice' => 0,
                        'finalPrice' => 3000,
                    ],
                ],
                200,
            ],
            [
                [
                    'products' => [
                        [
                            'type' => 'book',
                            'name' => 'Quantum Mechanics',
                            'price' => 3000,
                        ],
                    ],
                ],
                2,
                [
                    'error' => 'Order with Id 2 was not found',
                ],
                400,
            ],
        ];
    }

    private function createOrGetProductType(string $name, ProductTypeRepository $productTypeRepository, EntityManagerInterface $entityManager): ProductType
    {
        $productType = $productTypeRepository->findOneByName($name);
        if ($productType !== null) {
            return $productType;
        }
        $productType = new ProductType();
        $productType->setName($name);
        $entityManager->persist($productType);
        $entityManager->flush();
        return $productType;
    }

    private function parseJsonResponse(string $response): array
    {
        return json_decode($response, true);
    }

    private function createDb() {
        $this->runCommand('doctrine:database:drop --if-exists --force');
        $this->runCommand('doctrine:database:create -n');
        $this->runCommand('doctrine:migrations:migrate -n');
    }

    private function runCommand(string $command) {
        $this->application->run(new StringInput(sprintf('%s --quiet', $command)));
    }
}
