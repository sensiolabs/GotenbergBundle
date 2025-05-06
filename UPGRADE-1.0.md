# UPGRADE FROM 0.4.0 to 1.0.0

## Breaking changes

* Renamed method `errorWebhookUrl` to `webhookErrorUrl`
* Renamed argument `webhookExtraHeaders(array $extraHeaders)` to `webhookExtraHeaders(array $extraHttpHeaders)`
* Renamed arguments of the `nativePageRanges` method `$range` to `$ranges`
* Renamed arguments of the `setCookie` method `$key` to `$name`
