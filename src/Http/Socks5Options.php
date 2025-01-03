<?php

namespace JPCaparas\LaravelHttpSocks5\Http;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Socks5Options
{
    private string $host;
    private int $port = 1080;
    private ?string $username = null;
    private ?string $password = null;

    public function setHost(string $host): self
    {
        $this->host = $host;
        return $this;
    }

    public function setPort(int $port): self
    {
        $this->port = $port;
        return $this;
    }

    public function setCredentials(string $username, string $password): self
    {
        $this->username = $username;
        $this->password = $password;
        return $this;
    }

    private function toPendingRequest(): PendingRequest
    {
        $options = [
            'proxy' => sprintf('socks5://%s:%d', $this->host, $this->port)
        ];

        if ($this->username && $this->password) {
            $options['proxy'] = sprintf(
                'socks5://%s:%s@%s:%d',
                $this->username,
                $this->password,
                $this->host,
                $this->port
            );
        }

        return Http::withOptions($options);
    }

    /**
     * The __call magic method in PHP is triggered when invoking inaccessible methods in an object context. This includes:
     * - Calling a method that does not exist in the class.
     * - Calling a method that is not visible (e.g., private or protected) from the current scope.
     * 
     * This effectively proxies all method calls to the underlying PendingRequest instance.
     */
    public function __call($method, $parameters)
    {
        return $this->toPendingRequest()->$method(...$parameters);
    }
}
