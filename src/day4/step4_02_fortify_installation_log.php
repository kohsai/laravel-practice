<?php
/*
Fortify インストールログ

コマンド:
docker-compose exec php composer require laravel/fortify

出力:
WARN[0000] /home/kohsai/venpro/laravel-practice/docker-compose.yml: the attribute `version` is obsolete, it will be ignored, please remove it to avoid potential confusion
./composer.json has been updated
Running composer update laravel/fortify
Loading composer repositories with package information
Updating dependencies
Lock file operations: 5 installs, 0 updates, 0 removals
- Locking bacon/bacon-qr-code (v3.0.1)
- Locking dasprid/enum (1.0.7)
- Locking laravel/fortify (v1.30.0)
- Locking paragonie/constant_time_encoding (v3.0.0)
- Locking pragmarx/google2fa (v8.0.3)
Writing lock file
Installing dependencies from lock file (including require-dev)
Package operations: 5 installs, 0 updates, 0 removals
- Downloading dasprid/enum (1.0.7)
- Downloading paragonie/constant_time_encoding (v3.0.0)
- Downloading pragmarx/google2fa (v8.0.3)
- Downloading bacon/bacon-qr-code (v3.0.1)
- Downloading laravel/fortify (v1.30.0)
- Installing dasprid/enum (1.0.7): Extracting archive
- Installing paragonie/constant_time_encoding (v3.0.0): Extracting archive
- Installing pragmarx/google2fa (v8.0.3): Extracting archive
- Installing bacon/bacon-qr-code (v3.0.1): Extracting archive
- Installing laravel/fortify (v1.30.0): Extracting archive
1 package suggestions were added by new dependencies, use `composer suggest` to see details.
Generating optimized autoload files
> Illuminate\Foundation\ComposerScripts::postAutoloadDump
> @php artisan package:discover --ansi

INFO  Discovering packages.

laravel/fortify ................................................ DONE
laravel/sail ................................................... DONE
laravel/sanctum ................................................ DONE
laravel/tinker ................................................. DONE
nesbot/carbon .................................................. DONE
nunomaduro/collision ........................................... DONE
nunomaduro/termwind ............................................ DONE
spatie/laravel-ignition ........................................ DONE

80 packages you are using are looking for funding.
Use the `composer fund` command to find out more!
> @php artisan vendor:publish --tag=laravel-assets --ansi --force

    INFO  No publishable resources for tag [laravel-assets].

No security vulnerability advisories found.
Using version ^1.30 for laravel/fortify
*/
