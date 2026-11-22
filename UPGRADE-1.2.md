# UPGRADE FROM 1.1.0 to 1.2.0

## Breaking changes

* `Sensiolabs\GotenbergBundle\GotenbergPdfInterface` new method `encrypt` added
* `Sensiolabs\GotenbergBundle\GotenbergPdfInterface` new method `embed` added
* Since 1.1.1, `AbstractBuilder` and the `Builder\Behaviors\Dependencies\*AwareTrait` traits use
  `Symfony\Contracts\Service\ServiceMethodsSubscriberTrait` instead of `ServiceSubscriberTrait`. A custom builder that
  still uses `ServiceSubscriberTrait` gets a trait collision: remove it, `AbstractBuilder` already provides the
  subscriber and reads the same `#[SubscribedService]` attributes.

  ```diff
   final class MyBuilder extends AbstractBuilder
   {
  -    use ServiceSubscriberTrait;
   }
  ```

## Bug Fixes
* Since 1.1.1, the webhook configurations defined under `sensiolabs_gotenberg.webhook` are registered, so
  `config_name` and `webhookConfiguration()` find them.
* When AssetMapper is installed, the `gotenberg_*` Twig asset functions look the path up in the AssetMapper repository
  and send the source file.

## Configuration Changes
* A `version` lower than 8 is no longer rejected when the container is compiled: it throws an
  `\InvalidArgumentException` when the version is read.

## Testing
* `GotenbergBuilderTestCase::assertGotenbergFormDataFile()` now also checks the form field name given as its first
  argument. It was ignored before.
