# UPGRADE FROM 1.0.0 to 1.1.0

## Breaking changes
* `Sensiolabs\GotenbergBundle\NodeBuilder\EnumNodeBuilder::__construct()` has a new `$required` argument before
  `$values`. Pass `values:` and `callback:` as named arguments.

  ```diff
  - new EnumNodeBuilder('format', null, ['png', 'jpeg'])
  + new EnumNodeBuilder('format', values: ['png', 'jpeg'])
  ```
* `FlattenPdfBuilder` and `SplitPdfBuilder` throw a `VersionCompatibilityException` when the Gotenberg version is
  lower than 8.16 and 8.15, the versions that introduced their routes.

## Behavior Changes
* A relative asset path that exists in none of the `assets_directory` now throws a
  `Symfony\Component\Filesystem\Exception\FileNotFoundException` as soon as it is resolved. `contentFile()`,
  `headerFile()` and `footerFile()` used to throw a `PartRenderingException`.
* The `gotenberg_asset()`, `gotenberg_font_face()` and `gotenberg_font_style_tag()` Twig functions resolve their path
  through the Asset component when it is enabled: the path returned by the package (base path, version) is the one
  looked up in `assets_directory`.

## Configuration Changes
* Added new `sensiolabs_gotenberg.version` to add warning when trying to use a feature not yet available in Gotenberg.
  > [!NOTE]
  > If no version is explicitly defined in the configuration, the bundle will perform an additional HTTP call to the 
  > Gotenberg API to resolve the compatible version automatically.
* `assets_directory` accepts a list of directories, searched in order.

## Deprecations
* `SensiolabsGotenbergExtension::setBuilderStack()` is deprecated: the extension creates its `BuilderStack` itself.

## Dependency Changes
* Added the `ext-filter` requirement.
* Symfony 8 is supported.

## Contributions changes
* Move to [dagger.io](https://dagger.io/) to run tests both locally and in CI.

## DX Changes
* with `autoconfigure: true` and `implements BuilderInterface`, `configurator` will be automatically added.
