## Nest Sensor changer

Uses Laravel Dusk to navigate the nest website to change temperature sensors.
This is because the Nest API does not support sensors and the Google API is not generally available yet.

To use this:
 * You must not have connected your Nest account to your Google account.
 * Disable Nest 2fa
 * Set credentials with `php artisan nest:credentials --save` *Note password is stored insecurely
 * Run with `NEST_SENSOR_NAME="Upstairs" php artisan dusk` to change the active sensor

## Variables
| env var | description |
|---------|-------------|
| NEST_USERNAME | encrypted nest email |
| NEST_PASSWORD | encrypted nest password |
| NEST_THERMOSTAT | nest thermostat id (if multiple) |
| NEST_SENSOR_NAME | nest sensor name to activate as shown on the website |
