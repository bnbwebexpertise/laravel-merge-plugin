<?php namespace Bnb\Laravel\MergePlugin;

use Bnb\Laravel\Attachments\Console\Commands\CleanupAttachments;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\ServiceProvider;

class MergePluginServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->app->instance(PackageManifest::class, new MergePluginPackageManifest(
            $this->app->make(PackageManifest::class)
        ));
    }
}
