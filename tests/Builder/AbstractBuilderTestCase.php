<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sensiolabs\GotenbergBundle\Client\GotenbergClientInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;
use Sensiolabs\GotenbergBundle\Twig\GotenbergAssetExtension;
use Sensiolabs\GotenbergBundle\Webhook\WebhookConfigurationRegistryInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

abstract class AbstractBuilderTestCase extends TestCase
{
    protected const FIXTURE_DIR = __DIR__.'/../Fixtures';

    protected static UrlGeneratorInterface $urlGenerator;

    protected static Environment $twig;

    protected static AssetBaseDirFormatter $assetBaseDirFormatter;

    /**
     * @var MockObject&GotenbergClientInterface
     */
    protected GotenbergClientInterface $gotenbergClient;

    /**
     * @var MockObject&WebhookConfigurationRegistryInterface
     */
    protected WebhookConfigurationRegistryInterface $webhookConfigurationRegistry;

    public static function setUpBeforeClass(): void
    {
        $routeCollection = new RouteCollection();
        $routeCollection->add('_webhook_controller', new Route('/webhook/{type}'));
        self::$urlGenerator = new UrlGenerator($routeCollection, new RequestContext());
        self::$twig = new Environment(new FilesystemLoader(self::FIXTURE_DIR), [
            'strict_variables' => true,
        ]);
        self::$twig->addExtension(new GotenbergAssetExtension());
        self::$assetBaseDirFormatter = new AssetBaseDirFormatter(new Filesystem(), self::FIXTURE_DIR, self::FIXTURE_DIR);
    }

    protected function setUp(): void
    {
        $this->gotenbergClient = $this->createMock(GotenbergClientInterface::class);
        $this->webhookConfigurationRegistry = $this->createMock(WebhookConfigurationRegistryInterface::class);
    }

    /**
     * @param array<mixed> $data
     */
    protected static function assertFile(array $data, string $filename, string $contentType = 'text/html', string|null $expectedContent = null): void
    {
        self::assertArrayHasKey('files', $data);

        $file = $data['files'];

        self::assertInstanceOf(DataPart::class, $file);
        self::assertSame($filename, $file->getFilename());
        self::assertSame($contentType, $file->getContentType());

        if (null !== $expectedContent) {
            self::assertSame($expectedContent, $file->getBody());
        }

        iterator_to_array($file->bodyToIterable()); // Check if path is correct
    }
}
