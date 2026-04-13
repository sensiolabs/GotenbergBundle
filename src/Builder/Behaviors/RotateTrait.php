<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

use Sensiolabs\GotenbergBundle\Builder\Attributes\NormalizeGotenbergPayload;
use Sensiolabs\GotenbergBundle\Builder\Attributes\WithConfigurationNode;
use Sensiolabs\GotenbergBundle\Builder\Behaviors\Dependencies\LoggerAwareTrait;
use Sensiolabs\GotenbergBundle\Builder\BodyBag;
use Sensiolabs\GotenbergBundle\Builder\Util\NormalizerFactory;
use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Sensiolabs\GotenbergBundle\Enumeration\RotateAngle;
use Sensiolabs\GotenbergBundle\NodeBuilder\NativeEnumNodeBuilder;
use Sensiolabs\GotenbergBundle\NodeBuilder\ScalarNodeBuilder;

trait RotateTrait
{
    use LoggerAwareTrait;

    abstract protected function getBodyBag(): BodyBag;

    /**
     * The rotation angle.
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/rotate-pdfs
     *
     * @example rotateAngle(RotateAngle::Rotate90)
     */
    #[WithConfigurationNode(new NativeEnumNodeBuilder('rotate_angle', enumClass: RotateAngle::class))]
    public function rotateAngle(RotateAngle|null $rotateAngle = null): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The rotateAngle option is not available.');

        if (null === $rotateAngle) {
            $this->getBodyBag()->unset('rotateAngle');

            return $this;
        }

        $this->getBodyBag()->set('rotateAngle', $rotateAngle);

        return $this;
    }

    /**
     * Page ranges to rotate (e.g., '1-3', '5'). Empty means all pages.
     *
     * @see https://gotenberg.dev/docs/manipulate-pdfs/rotate-pdfs
     *
     * @example rotatePages('1-2')
     */
    #[WithConfigurationNode(new ScalarNodeBuilder('rotate_pages'))]
    public function rotatePages(string|null $rotatePages = null): static
    {
        $this->logWarningIfVersionIs('<', '8.28', 'The rotatePages option is not available.');

        if (!$rotatePages) {
            $this->getBodyBag()->unset('rotatePages');
        } else {
            ValidatorFactory::range($rotatePages);
            $this->getBodyBag()->set('rotatePages', $rotatePages);
        }

        return $this;
    }

    #[NormalizeGotenbergPayload]
    private function normalizeRotate(): \Generator
    {
        yield 'rotateAngle' => NormalizerFactory::enum();
    }
}
