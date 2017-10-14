<?php namespace Bnb\Laravel\MergePlugin;

use Illuminate\Foundation\PackageManifest;

class MergePluginPackageManifest extends PackageManifest
{

    /**
     * @var PackageManifest
     */
    private $framework;


    /**
     * MergePluginPackageManifest constructor.
     *
     * @param PackageManifest $framework
     */
    public function __construct(PackageManifest $framework)
    {
        parent::__construct($framework->files, $framework->basePath, $framework->manifestPath);

        $this->framework = $framework;
        $this->manifest = $framework->manifest;
    }


    public function build()
    {
        $this->manifest = $this->framework->manifest;

        $packages = collect($this->filesToScan())->map(function ($file) {
            return $this->packageFromFile($file);
        })->filter()->all();

        $ignoreAll = in_array('*', $ignore = $this->packagesToIgnore());

        $this->manifest = $this->framework->manifest + collect($packages)->mapWithKeys(function ($package) {
                return [$this->format($package['name']) => $package['extra']['laravel'] ?? []];
            })->each(function ($configuration) use (&$ignore) {
                $ignore += $configuration['dont-discover'] ?? [];
            })->reject(function ($configuration, $package) use ($ignore, $ignoreAll) {
                return $ignoreAll || in_array($package, $ignore);
            })->filter()->all();

        $this->write($this->manifest);
    }


    protected function filesToScan()
    {
        if ( ! file_exists($this->basePath . '/composer.json')) {
            return [];
        }

        $composer = json_decode(file_get_contents(
            $this->basePath . '/composer.json'
        ), true);

        return array_reduce(array_map('glob', array_merge(
            $composer['extra']['merge-plugin']['include'] ?? [],
            $composer['extra']['merge-plugin']['require'] ?? []
        )), 'array_merge', []);
    }


    protected function packageFromFile($file)
    {
        if ( ! file_exists($this->basePath . '/' . $file)) {
            return null;
        }

        return json_decode(file_get_contents(
            $this->basePath . '/' . $file
        ), true);
    }
}