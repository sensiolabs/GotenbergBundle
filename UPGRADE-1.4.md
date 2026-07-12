# UPGRADE FROM 1.3.0 to 1.4.0

## Breaking changes

* `AbstractBuilder::getHeadersBag()` now retains `Gotenberg-Webhook-Extra-Http-Headers` as an array until the payload is built. Code reading this value directly must expect an array instead of a JSON string.
