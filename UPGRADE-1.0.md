# UPGRADE FROM 0.4.0 to 1.0.0

## Breaking changes

* Renamed method `errorWebhookUrl` to `webhookErrorUrl`
* Renamed argument `webhookExtraHeaders(array $extraHeaders)` to `webhookExtraHeaders(array $extraHttpHeaders)`
* Renamed arguments of the `nativePageRanges` method `$range` to `$ranges`
* Renamed arguments of the `setCookie` method `$key` to `$name`
* Renamed arguments of the `marginBottom` method `$bottom` to `$value`
* Renamed arguments of the `marginLeft` method `$left` to `$value`
* Renamed arguments of the `marginRight` method `$right` to `$value`
* Renamed arguments of the `marginTop` method `$top` to `$value`
* Renamed arguments of the `paperHeight` method `$height` to `$value`
* Renamed arguments of the `paperWidth` method `$width` to `$value`

### Class and Behavior Changes
Removed `CookieAwareTrait`, `DefaultBuilderTrait` and `AsyncBuilderTrait` in favor of a more flexible approach

## Interface Changes
* Removed `AsyncBuilderInterface`, `PdfBuilderInterface` and `ScreenshotBuilderInterface` in favor of a more flexible approach base on a unified `BuilderInterface`
* Added new interfaces `BuilderAsyncInterface` and `BuilderFileInterface` that extend `BuilderInterface`
* Added new interface `BuilderAssetInterface` for asset management
* Introduced a new `AbstractBuilder` class that all builders now extend

## Service and DI Changes
* Replaced separate service tags `sensiolabs_gotenberg.pdf_builder` and `sensiolabs_gotenberg.screenshot_builder` with a unified `sensiolabs_gotenberg.builder` tag
* Builder services are now non-shared (a new instance is created each time)

## Configuration Changes
* Removed `WebhookConfigurationRegistry` and `WebhookConfigurationRegistryInterface` in favor of a more flexible approach
* Added new `BuilderConfigurator` service for configuring builders
* Builder configuration now uses PHP attributes (`WithBuilderConfiguration` and `WithConfigurationNode`) for defining configuration options
* Improved configuration system with `BuilderConfigurator`
