## Using the native feature

Gotenberg [allows](https://gotenberg.dev/docs/configuration#webhook) to defer the generation of your files through webhooks.
When it is done creating your file, it calls back whatever header you sent.

To use this feature you need two things :
- Send the appropriate headers
- use `->generateAsync()` method

### Through Bundle configuration

Using bundle configuration you can define :
- named configurations
- default named configuration
- per context (PDF+HTML, PDF+URL, SCREENSHOT+MARKDOWN)

```yaml
# config/packages/sensiolabs_gotenberg.yaml

sensiolabs_gotenberg:

    webhook:

        # Prototype
        name:
            name:                 ~
            success:

                # The URL to call.
                url:                  ~

                # Route configuration.
                route:                ~

                  # Examples:
                  # - 'https://webhook.site/#!/view/{some-token}'
                  # - [my_route, { param1: value1, param2: value2 }]

                # HTTP method to use on that endpoint.
                method:               null # One of "POST"; "PUT"; "PATCH"
            error:

                # The URL to call.
                url:                  ~

                # Route configuration.
                route:                ~

                  # Examples:
                  # - 'https://webhook.site/#!/view/{some-token}'
                  # - [my_route, { param1: value1, param2: value2 }]

                # HTTP method to use on that endpoint.
                method:               null # One of "POST"; "PUT"; "PATCH"

            # HTTP headers to send back to both success and error endpoints - default None. https://gotenberg.dev/docs/webhook
            extra_http_headers:

                # Prototype
                name:
                    name:                 ~
                    value:                ~
    default_options:

        # Webhook configuration name.
        webhook:              ~
```

Each named configuration requires at least a `success` URL which can be set either through a plain URL (`sensiolabs_gotenberg.webhook.{name}.success.url`) or by using a defined route in your application (`sensiolabs_gotenberg.webhook.{name}.success.route`).

Here are some examples :

```yaml
sensiolabs_gotenberg:
    webhook:
        default:
            success: 
                url: 'https://webhook.site/#!/view/{some-uuid}'
```
or
```yaml
sensiolabs_gotenberg:
    webhook:
        default:
            success: 
                route: ['my_route', {'param1': 'value1'}]
```

Once a named configuration has been set, you can set it as a global default for all your builders :

```yaml
sensiolabs_gotenberg:
    default_options:
        webhook: 'default'
```

or set it per builder :

```yaml
sensiolabs_gotenberg:
    webhook:
        default:
            success:
                url: 'https://webhook.site/#!/view/{some-uuid}'
        pdf_html:
            success:
                url: 'https://webhook.site/#!/view/{some-other-uuid}'
    default_options:
        pdf:
            html:
                webhook:
                    config_name: 'pdf_html'
```

finally you can do it like so :

```yaml
sensiolabs_gotenberg:
    default_options:
        pdf:
            html:
                webhook:
                    success:
                        url: 'https://webhook.site/#!/view/{some-uuid}'
```

> [!WARNING]  
> When using both `config_name` and a custom configuration on a builder,
> it will load the named configuration and merge it with the builder's configuration.
> 
> See the following example : 

```yaml
sensiolabs_gotenberg:
    webhook:
        default:
            success:
                url: 'https://webhook.site/#!/view/{some-success-uuid}'
            error:
                url: 'https://webhook.site/#!/view/{some-error-uuid}'
    default_options:
        pdf:
            html:
                webhook:
                    config_name: 'default'
                    success:
                        url: 'https://webhook.site/#!/view/{some-other-uuid}'
```

is equivalent to :

```yaml
sensiolabs_gotenberg:
    default_options:
        pdf:
            html:
                webhook:
                    success:
                        url: 'https://webhook.site/#!/view/{some-other-uuid}'
                    error:
                        url: 'https://webhook.site/#!/view/{some-error-uuid}'
```

### At runtime

You can define webhook configuration at runtime.

If you defined some named configuration like seen earlier, the simplest way is then to do the following:

```diff
$builder = $this->gotenberg->pdf()->html()
+    ->webhookConfiguration('default')
    ->header('header.html.twig')
    ->content('html.html.twig', ['name' => 'Plop'])
    ->fileName('html.pdf')
;
```

Or you can also define manually using :

```diff
$builder = $this->gotenberg->pdf()->html()
+    ->webhookUrl($this->router->generate('my_route'))
    ->header('header.html.twig')
    ->content('html.html.twig', ['name' => 'Plop'])
    ->fileName('html.pdf')
;
```

> [!WARNING]  
> If combining both `->webhookConfiguration()` & `->webhookUrl()`, the order is important :
> 
> If calling `->webhookConfiguration()` first then `->webhookUrl()` will override only the "success" part.
> 
> If calling `->webhookUrl()` first then `->webhookConfiguration()` totally overrides previously set values.


> [!NOTE]  
> If only success URL is set, error URL will fallback to the success one.
