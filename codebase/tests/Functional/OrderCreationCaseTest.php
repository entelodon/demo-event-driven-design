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

class OrderCreationCaseTest extends WebTestCase
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

    public function testNotJsonRequest(): void
    {
        $this->client->request('POST', '/order');
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $jsonResponse = $this->parseJsonResponse($this->client->getResponse()->getContent());
        $this->assertSame([
            'error' => 'Only `application/json` requests are accepted!'
        ], $jsonResponse);
    }

    /**
     * @dataProvider invalidDataStructureProvider
     */
    public function testInvalidDataStructure(array $expected, array $params, array $products): void
    {
        $this->createDb();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        /** @var ProductTypeRepository $productTypeRepository */
        $productTypeRepository = $this->getContainer()->get(ProductTypeRepository::class);

        foreach ($products as $product) {
            $productEntity = new Product();
            $productEntity->setType($this->createOrGetProductType($product['type'], $productTypeRepository, $entityManager));
            $productEntity->setName($product['name']);
            $productEntity->setPrice($product['price']);
            $entityManager->persist($productEntity);
            $entityManager->flush();
        }

        $this->client->jsonRequest('POST', '/order', $params);
        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
        $jsonResponse = $this->parseJsonResponse($this->client->getResponse()->getContent());
        $this->assertSame($expected, $jsonResponse);
    }

    public function invalidDataStructureProvider(): array
    {
        return [
            [
                [
                    'error' => 'Missing Argument products'
                ],
                [],
                [],
            ],
            [
                [
                    'error' => 'Missing Argument promotionalCodes'
                ],
                [
                    'products' => [],
                ],
                [],
            ],
            [
                [
                    'error' => 'Product with ID 1 does not exist.',
                ],
                [
                    'products' => [1],
                    'promotionalCodes' => [],
                ],
                [],
            ],
            [
                [
                    'error' => 'No products were supplied',
                ],
                [
                    'products' => [],
                    'promotionalCodes' => [1],
                ],
                [],
            ],
            [
                [
                    'error' => 'Promotional Code with CODE 1 does not exist.',
                ],
                [
                    'products' => [1],
                    'promotionalCodes' => [1],
                ],
                [
                    [
                        'type' => 'tv',
                        'name' => 'Samsung TV 42in QLED',
                        'price' => 130000,
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider priceCalculationProvider
     */
    public function testPriceCalculation(array $expected, array $products, array $promotionalCodes): void
    {
        $this->createDb();
        /** @var EntityManagerInterface $entityManager */
        $entityManager = $this->getContainer()->get(EntityManagerInterface::class);
        /** @var ProductTypeRepository $productTypeRepository */
        $productTypeRepository = $this->getContainer()->get(ProductTypeRepository::class);

        $params = [
            'products' => [],
            'promotionalCodes' => [],
        ];

        foreach ($products as $product) {
            $productEntity = new Product();
            $productEntity->setType($this->createOrGetProductType($product['type'], $productTypeRepository, $entityManager));
            $productEntity->setName($product['name']);
            $productEntity->setPrice($product['price']);
            $entityManager->persist($productEntity);
            $entityManager->flush();
            $params['products'][] = $productEntity->getId();
        }

        foreach ($promotionalCodes as $promotionalCode) {
            $promotionalCodeEntity = new PromotionalCode();
            $promotionalCodeEntity->setExactAmount($promotionalCode['exactAmount']);
            $promotionalCodeEntity->setCode($promotionalCode['code']);
            $promotionalCodeEntity->setAmount($promotionalCode['amount']);
            /** @var string $productTypeName */
            foreach ($promotionalCode['types'] as $productTypeName) {
                $promotionalCodeEntity->addType($this->createOrGetProductType($productTypeName, $productTypeRepository, $entityManager));
            }
            $entityManager->persist($promotionalCodeEntity);
            $entityManager->flush();
            $params['promotionalCodes'][] = $promotionalCodeEntity->getCode();
        }

        $this->client->jsonRequest('POST', '/order', $params);
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
        $jsonResponse = $this->parseJsonResponse($this->client->getResponse()->getContent());
        $this->assertSame($expected, $jsonResponse);
    }

    public function priceCalculationProvider(): array
    {
        return [
            'No promo codes' => [
                [
                    'message' => [
                        'productNames' => [
                            'Samsung TV 42in QLED',
                            'LG TV 42in LED',
                            'Macbook PRO, M1 MAX 32G',
                        ],
                        'promotionalCodes' => [],
                        'price' => 619900,
                        'discountPrice' => 0,
                        'finalPrice' => 619900,
                    ],
                ],
                [
                    [
                        'type' => 'tv',
                        'name' => 'Samsung TV 42in QLED',
                        'price' => 130000,
                    ],
                    [
                        'type' => 'tv',
                        'name' => 'LG TV 42in LED',
                        'price' => 70000,
                    ],
                    [
                        'type' => 'laptop',
                        'name' => 'Macbook PRO, M1 MAX 32G',
                        'price' => 419900,
                    ],
                ],
                [],
            ],
            'Promo code for only one product type - percentage' => [
                [
                    'message' => [
                        'productNames' => [
                            'Samsung TV 42in QLED',
                            'LG TV 42in LED',
                            'Macbook PRO, M1 MAX 32G',
                        ],
                        'promotionalCodes' => [
                            'TV50',
                        ],
                        'price' => 619900,
                        'discountPrice' => 100000,
                        'finalPrice' => 519900,
                    ],
                ],
                [
                    [
                        'type' => 'tv',
                        'name' => 'Samsung TV 42in QLED',
                        'price' => 130000,
                    ],
                    [
                        'type' => 'tv',
                        'name' => 'LG TV 42in LED',
                        'price' => 70000,
                    ],
                    [
                        'type' => 'laptop',
                        'name' => 'Macbook PRO, M1 MAX 32G',
                        'price' => 419900,
                    ],
                ],
                [
                    [
                        'code' => 'TV50',
                        'amount' => 50,
                        'exactAmount' => false,
                        'types' => ['tv'],
                    ],
                ],
            ],
            'Promo code for only one product type - fixed - one product affected' => [
                [
                    'message' => [
                        'productNames' => [
                            'Samsung TV 42in QLED',
                            'LG TV 42in LED',
                            'Macbook PRO, M1 MAX 32G',
                        ],
                        'promotionalCodes' => [
                            'TV50',
                        ],
                        'price' => 619900,
                        'discountPrice' => 3000,
                        'finalPrice' => 616900,
                    ],
                ],
                [
                    [
                        'type' => 'tv',
                        'name' => 'Samsung TV 42in QLED',
                        'price' => 130000,
                    ],
                    [
                        'type' => 'tv',
                        'name' => 'LG TV 42in LED',
                        'price' => 70000,
                    ],
                    [
                        'type' => 'laptop',
                        'name' => 'Macbook PRO, M1 MAX 32G',
                        'price' => 419900,
                    ],
                ],
                [
                    [
                        'code' => 'TV50',
                        'amount' => 3000,
                        'exactAmount' => true,
                        'types' => ['tv'],
                    ],
                ],
            ],
            'Promo code for only one product type - fixed - three products affected - whole amount' => [
                [
                    'message' => [
                        'productNames' => [
                            'The entropy of black holes',
                            'Astrophysics for people in a hurry',
                            'Gravitational Waves',
                            'Macbook PRO, M1 MAX 32G',
                        ],
                        'promotionalCodes' => [
                            'BOOK60',
                        ],
                        'price' => 428900,
                        'discountPrice' => 6000,
                        'finalPrice' => 422900,
                    ],
                ],
                [
                    [
                        'type' => 'book',
                        'name' => 'The entropy of black holes',
                        'price' => 3000,
                    ],
                    [
                        'type' => 'book',
                        'name' => 'Astrophysics for people in a hurry',
                        'price' => 2000,
                    ],
                    [
                        'type' => 'book',
                        'name' => 'Gravitational Waves',
                        'price' => 4000,
                    ],
                    [
                        'type' => 'laptop',
                        'name' => 'Macbook PRO, M1 MAX 32G',
                        'price' => 419900,
                    ],
                ],
                [
                    [
                        'code' => 'BOOK60',
                        'amount' => 6000,
                        'exactAmount' => true,
                        'types' => ['book'],
                    ],
                ],
            ],
            'Promo code for only one product type - fixed - two products affected - not whole amount' => [
                [
                    'message' => [
                        'productNames' => [
                            'The entropy of black holes',
                            'Astrophysics for people in a hurry',
                            'Macbook PRO, M1 MAX 32G',
                        ],
                        'promotionalCodes' => [
                            'BOOK60',
                        ],
                        'price' => 424900,
                        'discountPrice' => 5000,
                        'finalPrice' => 419900,
                    ],
                ],
                [
                    [
                        'type' => 'book',
                        'name' => 'The entropy of black holes',
                        'price' => 3000,
                    ],
                    [
                        'type' => 'book',
                        'name' => 'Astrophysics for people in a hurry',
                        'price' => 2000,
                    ],
                    [
                        'type' => 'laptop',
                        'name' => 'Macbook PRO, M1 MAX 32G',
                        'price' => 419900,
                    ],
                ],
                [
                    [
                        'code' => 'BOOK60',
                        'amount' => 6000,
                        'exactAmount' => true,
                        'types' => ['book'],
                    ],
                ],
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
