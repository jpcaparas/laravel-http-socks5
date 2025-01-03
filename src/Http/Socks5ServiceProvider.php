<?php

namespace JPCaparas\LaravelHttpSocks5\Http;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;

class Socks5ServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Http::macro('socks5', function() {
            return new Socks5Options();
        });
    }
}
