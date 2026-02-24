# UPGRADE FROM 1.0.0 to 1.1.0

## Configuration Changes
* Added new `sensiolabs_gotenberg.version` to add warning when trying to use a feature not yet available in Gotenberg.
  > [!NOTE]
  > If no version is explicitly defined in the configuration, the bundle will perform an additional HTTP call to the 
  > Gotenberg API to resolve the compatible version automatically.

## Contributions changes
* Move to [dagger.io](https://dagger.io/) to run tests both locally and in CI.

## DX Changes
* with `autoconfigure: true` and `implements BuilderInterface`, `configurator` will be automatically added.
