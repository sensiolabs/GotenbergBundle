# UPGRADE FROM 1.0.0 to 1.1.0

## Configuration Changes
* Added new `sensiolabs_gotenberg.version` to add warning when trying to use a feature not yet available in Gotenberg.

## Contributions changes
* Move to [dagger.io](https://dagger.io/) to run tests both locally and in CI.

## DX Changes
* with `autoconfigure: true` and `implements BuilderInterface`, `configurator` will be automatically added.
