<?php

namespace JpCaparas\LaravelHttpSocks5\Tests\Integration;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase;

class Socks5ClientTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Prevent stray requests from being made. Always fake requests.
        Http::preventStrayRequests();
    }

    protected function getPackageProviders($app): array
    {
        return [
            'JpCaparas\LaravelHttpSocks5\Http\Socks5ServiceProvider',
        ];
    }

    public function testSocks5ProxyGet(): void
    {
        Http::fake([
            'google.com/*' => Http::response('OK', 200)
        ]);

        $client = Http::socks5()
            ->setHost('proxy.example.com')
            ->setPort(1080)
            ->setCredentials('user', 'pass')
            ->withOptions(['debug' => true]);

        $response = $client->get('https://google.com/search');

        Http::assertSent(function (Request $request) use ($client) {
            return $request->url() === 'https://google.com/search' &&
                $client->getOptions()['proxy'] === 'socks5://user:pass@proxy.example.com:1080';
        });

        $this->assertTrue($response->ok());
        $this->assertEquals('OK', $response->body());
    }

    public function testSocks5ProxyPost(): void
    {
        Http::fake([
            'api.example.com/*' => Http::response(['status' => 'success'], 201)
        ]);

        $client = Http::socks5()
            ->setHost('proxy.example.com')
            ->setCredentials('user', 'pass')
            ->withOptions(['debug' => true]);

        $response = $client->post('https://api.example.com/data', ['key' => 'value']);

        Http::assertSent(function (Request $request) use ($client) {
            return $request->url() === 'https://api.example.com/data' &&
                $client->getOptions()['proxy'] === 'socks5://user:pass@proxy.example.com:1080';
        });

        $this->assertEquals(201, $response->status());
        $this->assertEquals(['status' => 'success'], $response->json());
    }

    public function testSocks5ProxyError(): void
    {
        Http::fake([
            '*' => Http::response(null, 500)
        ]);

        $response = Http::socks5()
            ->setHost('proxy.example.com')
            ->get('https://api.example.com');

        $this->assertTrue($response->failed());
        $this->assertEquals(500, $response->status());
    }
}
