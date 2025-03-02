# Laravel Schedule Manager

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zwartpet/schedule-manager.svg?style=flat-square)](https://packagist.org/packages/zwartpet/schedule-manager)
[![Test](https://github.com/Zwartpet/laravel-schedule-manager/actions/workflows/main.yml/badge.svg)](https://github.com/Zwartpet/laravel-schedule-manager/actions/workflows/main.yml)

Adds pause and resume functionality to the Laravel schedule via command line and an admin interface (wip).  
Because sometimes you just quickly want to pause a specific schedule without the need to deploy the whole application

## Installation

You can install the package via composer:

```bash
composer require zwartpet/schedule-manager
```

## Usage

### Command line

Just use the `pause` and `resume` commands to pause and resume a schedule.    
With the `--description` option you can add a description to the pause.  
The `--pause-until` option will pause the schedule until the given date.
```bash
php artisan schedule:pause --description="3rd party API is down" --pause-until="2027-01-01 00:00:00"
```
```bash
 ┌ Which schedule do you want to pause? ────────────────────────┐
 │ › ● 0 0 * * * php artisan cache:clear                        │
 │   ○ 0 0 1 * * php artisan cache:clear                        │
 └──────────────────────────────────────────────────────────────┘
```

Using the `paused` command you can see all the paused schedules.
```bash
php artisan schedule:paused     
```         
```bash
 ┌───────────────────────────────────┬─────────────────────┬───────────────────────┐
 │ Paused schedule                   │ Paused until        │ Description           │
 ├───────────────────────────────────┼─────────────────────┼───────────────────────┤
 │ 0 0 * * * php artisan cache:clear │ 2027-01-01 00:00:00 │ 3rd party API is down │
 └───────────────────────────────────┴─────────────────────┴───────────────────────┘
```

Additionally there is a `schedule:optimize` command that will cleanup the paused schedules that have expired when using the database driver which is also running on `php artisan optimize`.

### Configuration

This library is plug and play and works out of the box. There are some configuration options available.

By default the package uses the Laravel cache to store the paused schedules.
You can change the cache store by adding `SCHEDULE_MANAGER_CACHE_STORE` to your `.env` which defaults to the Laravel's own `CACHE_STORE`

If you want to persist the pauses even further, for instance because deploys often clear the cache, you can use the database driver.  
Overwrite the `SCHEDULE_MANAGER_DRIVER` in your `.env` to `database` and run the migrations.

## Testing

Pest
```bash
composer test
```

Pint
```bash
composer test:lint
```

PHPStan
```bash
composer test:types
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Credits

-   [John Zwarthoed](https://github.com/zwartpet)
-   [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

## Laravel Package Boilerplate

This package was generated using the [Laravel Package Boilerplate](https://laravelpackageboilerplate.com).
