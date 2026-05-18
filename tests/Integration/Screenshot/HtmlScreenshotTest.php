<?php

namespace Sensiolabs\GotenbergBundle\Tests\Integration\Screenshot;

use Sensiolabs\GotenbergBundle\GotenbergScreenshotInterface;
use Sensiolabs\GotenbergBundle\Tests\Integration\AbstractGotenbergIntegrationTestCase;

final class HtmlScreenshotTest extends AbstractGotenbergIntegrationTestCase
{
    public function testItGeneratesScreenshotFromRawHtml(): void
    {
        /** @var GotenbergScreenshotInterface $gotenberg */
        $gotenberg = static::getContainer()->get(GotenbergScreenshotInterface::class);
        $result = $gotenberg
            ->html()
            ->fileName('integration-html-screenshot')
            ->contentRaw('<html><body style="background:#fff;"><div style="width:240px;height:120px;background:#f4d35e;">Screenshot</div></body></html>')
            ->generate()
        ;

        $this->assertBinaryFileResult($result, 'image/', '.png');
    }
}
