# UPGRADE FROM 1.0.0 to 1.1.0

## Configuration Changes
* Added new `sensiolabs_gotenberg.version` to add warning if trying to use a non-yet available feature from Gotenberg.

## Contributions changes
* Move to [dagger.io](https://dagger.io/) to run tests both locally and in CI.

## DX Changes
* with `autoconfigure: true` and `implements BuilderInterface`, `configurator` will be automatically added.
