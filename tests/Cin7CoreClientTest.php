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

     public function testPut()
        {
            $client = $this->getMockBuilder(Cin7CoreClient::class)
                ->setConstructorArgs([$this->accountId, $this->applicationKey])
                ->onlyMethods(['executeRequest'])
                ->getMock();

            $putData  = ['Name' => 'Updated Product'];
            $expected = ['ID' => '123'];

            $client->expects($this->once())
                ->method('executeRequest')
                ->with('PUT', 'https://inventory.dearsystems.com/ExternalApi/v2/Product', $putData)
                ->willReturn($expected);

            $this->assertSame($expected, $client->put('Product', $putData));
        }

       public function testDelete()
          {
              $client = $this->getMockBuilder(Cin7CoreClient::class)
                  ->setConstructorArgs([$this->accountId, $this->applicationKey])
                  ->onlyMethods(['executeRequest'])
                  ->getMock();

              $expected = ['Status' => 'Success'];

              $client->expects($this->once())
                  ->method('executeRequest')
                  ->with('DELETE', 'https://inventory.dearsystems.com/ExternalApi/v2/Product?ID=123', null)
                  ->willReturn($expected);

              $this->assertSame($expected, $client->delete('Product', ['ID' => 123]));
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

       public function testFetchAllYieldsNothingWhenListKeyMissing()
          {
              $client = $this->getMockBuilder(Cin7CoreClient::class)
                  ->setConstructorArgs([$this->accountId, $this->applicationKey])
                  ->onlyMethods(['executeRequest'])
                  ->getMock();

              // The response carries no 'Products' key at all.
              $client->expects($this->once())
                  ->method('executeRequest')
                  ->willReturn(['Status' => 'Success', 'Total' => 0]);

              $this->assertSame([], iterator_to_array($client->fetchAll('Product', 'Products')));
          }

        public function testFetchAllYieldsNothingWhenListKeyNotAnArray()
           {
               $client = $this->getMockBuilder(Cin7CoreClient::class)
                   ->setConstructorArgs([$this->accountId, $this->applicationKey])
                   ->onlyMethods(['executeRequest'])
                   ->getMock();

               // 'Products' is present but is not an array.
               $client->expects($this->once())
                   ->method('executeRequest')
                   ->willReturn(['Status' => 'Success', 'Total' => 0, 'Products' => 'unexpected']);

               $this->assertSame([], iterator_to_array($client->fetchAll('Product', 'Products')));
           }

        public function testInterpretResponseDecodesJsonOnSuccess()
           {
               $client = new TestableCin7CoreClient('account', 'key');

               $decoded = $client->runInterpret('{"Status":"Success","Total":2}', 200, '');

               $this->assertSame(['Status' => 'Success', 'Total' => 2], $decoded);
           }

        public function testInterpretResponseThrowsOnTransportError()
           {
               $client = new TestableCin7CoreClient('account', 'key');

               $this->expectException(\Exception::class);
               $this->expectExceptionMessage('cURL Error: Could not resolve host');

               $client->runInterpret(false, 0, 'Could not resolve host');
           }

        public function testInterpretResponseThrowsOnHttpError()
           {
               $client = new TestableCin7CoreClient('account', 'key');

               $this->expectException(\Exception::class);
               $this->expectExceptionMessage('API Error (404):');

               $client->runInterpret('{"Status":"Error","Message":"Not found"}', 404, '');
           }
}


/**
 * Testable subclass that exposes the protected response-handling logic so the
 * cURL/HTTP error branches can be exercised without a live socket.
 */
class TestableCin7CoreClient extends Cin7CoreClient
{
    public function runInterpret(mixed $response, int $httpCode, mixed $error): mixed
       {
        return $this->interpretResponse($response, $httpCode, $error);
       }
}
