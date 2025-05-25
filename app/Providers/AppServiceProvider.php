<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Add a custom disk for private files
        \Storage::extend('private', function ($app, $config) {
            $config['root'] = public_path('app/private/public');
            return new \Illuminate\Filesystem\FilesystemAdapter(
                new \League\Flysystem\Filesystem(
                    new \League\Flysystem\Local\LocalFilesystemAdapter(
                        $config['root']
                    )
                ),
                $config
            );
        });
    }
}
