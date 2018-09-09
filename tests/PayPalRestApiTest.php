<?php

namespace Srmklive\PayPal\Test;

use GuzzleHttp\Client as HttpClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Srmklive\PayPal\Services\PayPalRestAPI as Client;

class PayPalRestApiTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $client = new Client();

        $this->assertInstanceOf(Client::class, $client);
    }

    /** @test */
    public function it_can_set_a_valid_locale()
    {
        $locale = 'en_US';

        $client = new Client();
        $client->setLocale($locale);

        $this->assertNotEmpty($client->getLocale());
        $this->assertEquals($locale, $client->getLocale());
    }

    /** @test */
    public function it_throws_exception_when_setting_invalid_locale()
    {
        $this->expectException(\Exception::class);

        $client = new Client();
        $client->setLocale('en_PK');

        $this->assertEmpty($client->getLocale());
    }

    protected function setClient()
    {
        $client = new Client();
        $client->setApiCredentials(
            $this->credentials(),
            true
        );

        return $client;
    }

    protected function credentials()
    {
        return [
            'client'        => 'test-client-id',
            'secret'        => 'test-client-secret',
            'app_id'        => 'APP-80W284485P519543T',
            'validate_ssl'  => false,
            'locale'        => 'en_US',
        ];
    }

    private function mock_http_request($expectedResponse, $expectedEndpoint, $expectedParams)
    {
        $mockResponse = $this->getMockBuilder(ResponseInterface::class)
            ->getMock();
        $mockResponse->expects($this->once())
            ->method('getBody')
            ->willReturn($expectedResponse);

        $mockHttpClient = $this->getMockBuilder(HttpClient::class)
            ->setMethods(['post'])
            ->getMock();
        $mockHttpClient->expects($this->once())
            ->method('post')
            ->with($expectedEndpoint, $expectedParams)
            ->willReturn($mockResponse);

        return $mockHttpClient;
    }
}
