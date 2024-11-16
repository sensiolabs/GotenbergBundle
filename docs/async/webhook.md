## Using the Symfony Webhook component

Symfony provides a specific [Webhook component](https://symfony.com/doc/current/webhook.html) dedicated to this task.

Its role is to parse requests related to known webhooks and dispatch a corresponding remote event. Then, this event can
be handled by your application through the [Messenger component](https://symfony.com/doc/current/messenger.html).

The GotenbergBundle offers a native integration of this component if installed.

### Usage

To connect the provider to your application, you need to configure the Webhook component routing:

```yaml
# config/packages/webhook.yaml
framework:
  webhook:
    routing:
      gotenberg:
        service: 'sensiolabs_gotenberg.webhook.request_parser'
```

Then, create your handler to respond to the Gotenberg RemoteEvent:

```php
use Sensiolabs\GotenbergBundle\RemoteEvent\ErrorGotenbergEvent;
use Sensiolabs\GotenbergBundle\RemoteEvent\SuccessGotenbergEvent;
use Symfony\Component\RemoteEvent\Consumer\ConsumerInterface;
use Symfony\Component\RemoteEvent\Attribute\AsRemoteEventConsumer;
use Symfony\Component\RemoteEvent\RemoteEvent;

#[AsRemoteEventConsumer('gotenberg')]
class WebhookListener implements ConsumerInterface
{
    public function consume(RemoteEvent $event): void
    {
        if ($event instanceof SuccessGotenbergEvent) {
            // Handle the event
            // PDF content is available as a resource through the getFile() method
        } elseif ($event instanceof ErrorGotenbergEvent) {
            // Handle the error
        }
    }
}
```

> [!WARNING]  
> The webhook component **won't be used** if a [native webhook configuration](native.md) is set.
