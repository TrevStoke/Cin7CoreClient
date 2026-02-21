<?php

namespace Trevweb\Cin7CoreClient\Tests;

use PHPUnit\Framework\TestCase;
use Trevweb\Cin7CoreClient\Cin7CoreClient;

class Cin7CoreClientTest extends TestCase
{
    private $accountId = 'test-account';
    private $applicationKey = 'test-key';

    public function testGet()
    {
        $client = $this->getMockBuilder(Cin7CoreClient::class)
            ->setConstructorArgs([$this->accountId, $this->applicationKey])
            ->onlyMethods(['executeRequest'])
            ->getMock();

        $expectedResponse = ['Status' => 'Success', 'Products' => []];

        $client->expects($this->once())
            ->method('executeRequest')
            ->with('GET', 'https://inventory.dearsystems.com/ExternalApi/v2/Product?Name=Test', null)
            ->willReturn($expectedResponse);

        $response = $client->get('Product', ['Name' => 'Test']);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testPost()
    {
        $client = $this->getMockBuilder(Cin7CoreClient::class)
            ->setConstructorArgs([$this->accountId, $this->applicationKey])
            ->onlyMethods(['executeRequest'])
            ->getMock();

        $postData = ['Name' => 'New Product'];
        $expectedResponse = ['ID' => '123'];

        $client->expects($this->once())
            ->method('executeRequest')
            ->with('POST', 'https://inventory.dearsystems.com/ExternalApi/v2/Product', $postData)
            ->willReturn($expectedResponse);

        $response = $client->post('Product', $postData);

        $this->assertEquals($expectedResponse, $response);
    }

    public function testFetchAll()
    {
        $client = $this->getMockBuilder(Cin7CoreClient::class)
            ->setConstructorArgs([$this->accountId, $this->applicationKey])
            ->onlyMethods(['executeRequest'])
            ->getMock();

        // Mock two pages
        $client->expects($this->exactly(2))
            ->method('executeRequest')
            ->willReturnMap([
                ['GET', 'https://inventory.dearsystems.com/ExternalApi/v2/Product?Limit=100&Page=1', null, [
                    'Total' => 150,
                    'Products' => array_fill(0, 100, ['ID' => 'p1'])
                ]],
                ['GET', 'https://inventory.dearsystems.com/ExternalApi/v2/Product?Limit=100&Page=2', null, [
                    'Total' => 150,
                    'Products' => array_fill(0, 50, ['ID' => 'p2'])
                ]],
            ]);

        $results = iterator_to_array($client->fetchAll('Product', 'Products'));

        $this->assertCount(150, $results);
        $this->assertEquals('p1', $results[0]['ID']);
        $this->assertEquals('p2', $results[149]['ID']);
    }

    public function testFetchAllStopsWhenFewerThanLimit()
    {
        $client = $this->getMockBuilder(Cin7CoreClient::class)
            ->setConstructorArgs([$this->accountId, $this->applicationKey])
            ->onlyMethods(['executeRequest'])
            ->getMock();

        $client->expects($this->once())
            ->method('executeRequest')
            ->willReturn([
                'Total' => 50,
                'Products' => array_fill(0, 50, ['ID' => 'p1'])
            ]);

        $results = iterator_to_array($client->fetchAll('Product', 'Products'));

        $this->assertCount(50, $results);
    }
}
