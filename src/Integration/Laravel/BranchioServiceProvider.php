<?php

namespace Iivannov\Branchio\Integration\Laravel;

use Illuminate\Support\ServiceProvider;

class BranchioServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(\Iivannov\Branchio\Client::class, function ($app) {

            /** @var @see \Illuminate\Config\Repository $config */
            $config = $app->make('config');

            $key = $config->get('services.branchio.key');
            $secret = $config->get('services.branchio.secret');

            if (!$key || !$secret)
                throw new \InvalidArgumentException('Missing BranchIO key and secret in configuration files');

            return new \Iivannov\Branchio\Client($key, $secret);
        });
    }
}
