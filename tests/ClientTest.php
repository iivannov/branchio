<?php

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testClientThrowsForbiddenException()
    {
        $mock = new MockHandler([
            new ClientException("Forbidden", new Request('GET', 'url'), new Response(403)),
        ]);

        $handler = HandlerStack::create($mock);
        $muzzle = new Client(['handler' => $handler]);

        $this->expectException(\Iivannov\Branchio\Exceptions\BranchioForbiddenException::class);

        $client = new \Iivannov\Branchio\Client('key', 'secret', $muzzle);
        $client->getLink('url');
    }

    public function testClientThrowsNotFoundException()
    {
        $mock = new MockHandler([
            new ClientException("Not Found", new Request('GET', 'url'), new Response(404)),
        ]);

        $handler = HandlerStack::create($mock);
        $muzzle = new Client(['handler' => $handler]);

        $this->expectException(\Iivannov\Branchio\Exceptions\BranchioNotFoundException::class);

        $client = new \Iivannov\Branchio\Client('key', 'secret', $muzzle);
        $client->getLink('url');
    }


    public function testClientThrowsDuplicateException()
    {
        $mock = new MockHandler([
            new ClientException("Duplicate", new Request('GET', 'url'), new Response(409)),
        ]);

        $handler = HandlerStack::create($mock);
        $muzzle = new Client(['handler' => $handler]);

        $this->expectException(\Iivannov\Branchio\Exceptions\BranchioDuplicateLinkException::class);

        $client = new \Iivannov\Branchio\Client('key', 'secret', $muzzle);
        $client->getLink('url');

    }


}

