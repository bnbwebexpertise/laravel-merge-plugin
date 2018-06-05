# Laravel 5.* - Wikimedia Composer Merge Plugin - Auto Discovery

> Intended to be used for Laravel packages development only and when [wikimedia/composer-merge-plugin](https://github.com/wikimedia/composer-merge-plugin) is used to load the packages.

__Wikimedia Composer Merge Plugin__ do not add the loaded plugins to the `installed.json` of __Composer__.
This package scans the plugins loaded via __Wikimedia Composer Merge Plugin__ to add their`composer.json` auto-discovery bindings to __Laravel__'s cached `packages.php`.


    composer require --dev bnbwebexpertise/laravel-merge-plugin
