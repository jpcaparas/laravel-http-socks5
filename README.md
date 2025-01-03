# Laravel HTTP client macro for SOCKS5 proxying

![Tests](https://github.com/jpcaparas/laravel-http-socks5/actions/workflows/tests.yml/badge.svg)

This package extends the Laravel HTTP service to allow for easy, parameterised SOCKS5 proxying

## Installation

You can install the package via composer:

```bash
composer require jpcaparas/laravel-http-socks5
```

## Usage

### Setting up the SOCKS5 Proxy

To use the SOCKS5 proxy, you can utilize the fluent interface:

```php
use Illuminate\Support\Facades\Http;

$response = Http::socks5()
    ->setHost('proxy.example.com')
    ->setPort(1080)
    ->setCredentials('username', 'password')
    ->get('http://example.com');
```

### Method Chaining

You can chain multiple configuration methods:

```php
use Illuminate\Support\Facades\Http;

$client = Http::socks5()
    ->setHost('proxy.example.com')
    ->setCredentials('username', 'password')
    ->withOptions([
        'debug' => true,
        'timeout' => 30
    ]);

$response = $client->post('https://api.example.com/data', [
    'key' => 'value'
]);
```

### Debugging the Response

Enable debugging to see detailed connection information:

```php
use Illuminate\Support\Facades\Http;

$response = Http::socks5()
    ->setHost('proxy.example.com')
    ->setCredentials('username', 'password')
    ->withOptions(['debug' => true])
    ->get('http://example.com');
```

## Trying it out

You can experiment with the package using Laravel Tinker. First, run:

```bash
composer tinker
```

Then try this example (outputs detailed connection info):

```php
$client = \Illuminate\Support\Facades\Http::socks5()
   ->setHost('amsterdam.nl.socks.nordhold.net')
   ->setCredentials('username', 'password');

$client->withOptions(['debug' => true])->get('https://www.google.com');
```

Example output:
```
* Host amsterdam.nl.socks.nordhold.net:1080 was resolved.
* IPv6: (none)
* IPv4: [REDACTED]
*   Trying [REDACTED]:1080...
* Host www.google.com:443 was resolved.
* IPv6: [REDACTED]
* IPv4: [REDACTED]
* SOCKS5 connect to [REDACTED]:443 (locally resolved)
* SOCKS5 request granted.
* Connected to amsterdam.nl.socks.nordhold.net port 1080
[...]
* SSL connection using TLSv1.3
* Server certificate verified
> GET / HTTP/1.1
Host: www.google.com
[...]
< HTTP/1.1 200 OK
< Date: Fri, 03 Jan 2025 08:14:37 GMT
[...]
```
