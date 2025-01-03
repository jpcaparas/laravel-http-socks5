<?php

namespace JPCaparas\LaravelHttpSocks5\Http;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class Socks5ServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Http::macro('socks5', function () {
            return new Socks5Options;
        });
    }
}
