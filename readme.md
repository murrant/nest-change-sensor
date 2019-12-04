## Nest Sensor changer

Uses Laravel Dusk to navigate the nest website to change temperature sensors.
This is because the Nest API does not support sensors and the Google API is not generally available yet.

To use this:
 * You must not have connected your Nest account to your Google account.
 * Disable Nest 2fa
 * Set credentials with `php artisan nest:credentials --save` *Note password is stored insecurely
 * Google Chrome installed
 * Chrome Driver installed via `php artisan dusk:chrome-driver`
 * Run with `NEST_SENSOR_NAME="Upstairs" php artisan dusk` to change the active sensor

## Setup

```bash
cd /opt
git clone https://github.com/murrant/nest-change-sensor.git
cd nest-change-sensor
cp .env.example .env
composer install
php artisan key:generate
php artisan nest:credentials --save
php artisan dusk:chrome-driver
```

## Test

```bash
NEST_SENSOR_NAME="Upstairs" php artisan dusk
```

## Set up cron

You may wish to have the script run on a schedule

## Variables
| env var | description |
|---------|-------------|
| NEST_USERNAME | encrypted nest email |
| NEST_PASSWORD | encrypted nest password |
| NEST_THERMOSTAT | nest thermostat id (if multiple) |
| NEST_SENSOR_NAME | nest sensor name to activate as shown on the website |
| DUSK_DRIVER | set to chrome to use chrome |
| CHROME_HEADLESS | set to false to show the Chrome window onscreen |
