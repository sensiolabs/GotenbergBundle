<?php

declare(strict_types=1);

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Formatter\AssetBaseDirFormatter;

/**
 * @template T of BuilderInterface
 */
trait EmbedTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    public function testWithFileToEmbed(): void
    {
        $this->withGotenbergVersion('8.25.0');
        $this->container->set('asset_base_dir_formatter', new AssetBaseDirFormatter(self::FIXTURE_DIR, [self::FIXTURE_DIR]));

        $this->getDefaultBuilder()
            ->filename('testEmbed.pdf')
            ->embedFiles('embed/facturX.xml')
            ->generate()
        ;

        $this->assertGotenbergFormDataFile('embeds', 'application/xml', self::FIXTURE_DIR.'/embed/facturX.xml');
    }
}
