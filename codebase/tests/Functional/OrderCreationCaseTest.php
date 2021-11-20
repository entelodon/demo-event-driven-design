<?php

namespace App\Tests\Functional;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
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

    private function runCommand(string $command) {
        $this->application->run(new StringInput(sprintf('%s --quiet', $command)));
    }

    public function testNotAllowedMethod(): void
    {
        $this->client->catchExceptions(false);
        $this->expectException(MethodNotAllowedHttpException::class);
        $this->client->request('GET', '/order');
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
    public function testInvalidDataStructure(array $expected, array $params): void
    {
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
            ],
            [
                [
                    'error' => 'Missing Argument promotionalCodes'
                ],
                [
                    'products' => [],
                ],
            ],
            [
                [
                    'error' => 'Missing Argument products'
                ],
                [
                    'products' => [1,2,3,23423],
                    'promotionalCodes' => [],
                ],
            ],
        ];
    }

    private function parseJsonResponse(string $response): array
    {
        return json_decode($response, true);
    }

    private function createDb() {
        $this->runCommand('doctrine:database:create -n');
        $this->runCommand('doctrine:migrations:migrate -n');
    }
}
