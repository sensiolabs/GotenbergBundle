<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\LibreOffice;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\ImageResolutionDPI;
use Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\BehaviorTrait;

/**
 * @template T of BuilderInterface
 */
trait PagePropertiesTestCaseTrait
{
    /** @use BehaviorTrait<T> */
    use BehaviorTrait;

    abstract protected function assertGotenbergFormData(string $field, string $expectedValue): void;

    public function testSetPassword(): void
    {
        $this->getDefaultBuilder()
            ->password('my_password')
            ->generate()
        ;

        $this->assertGotenbergFormData('password', 'my_password');
    }

    public function testSetOrientationToLandscape(): void
    {
        $this->getDefaultBuilder()
            ->landscape()
            ->generate()
        ;

        $this->assertGotenbergFormData('landscape', 'true');
    }

    public function testNativePageRangesForRendering(): void
    {
        $this->getDefaultBuilder()
            ->nativePageRanges('1-2')
            ->generate()
        ;

        $this->assertGotenbergFormData('nativePageRanges', '1-2');
    }

    public function testDoNotExportFormFields(): void
    {
        $this->getDefaultBuilder()
            ->doNotExportFormFields()
            ->generate()
        ;

        $this->assertGotenbergFormData('exportFormFields', 'false');
    }

    public function testAllowDuplicateFieldNames(): void
    {
        $this->getDefaultBuilder()
            ->allowDuplicateFieldNames()
            ->generate()
        ;

        $this->assertGotenbergFormData('allowDuplicateFieldNames', 'true');
    }

    public function testDoNotExportBookmarks(): void
    {
        $this->getDefaultBuilder()
            ->doNotExportBookmarks()
            ->generate()
        ;

        $this->assertGotenbergFormData('exportBookmarks', 'false');
    }

    public function testExportBookmarksToPdfDestination(): void
    {
        $this->getDefaultBuilder()
            ->exportBookmarksToPdfDestination()
            ->generate()
        ;

        $this->assertGotenbergFormData('exportBookmarksToPdfDestination', 'true');
    }

    public function testExportPlaceholders(): void
    {
        $this->getDefaultBuilder()
            ->exportPlaceholders()
            ->generate()
        ;

        $this->assertGotenbergFormData('exportPlaceholders', 'true');
    }

    public function testExportNotes(): void
    {
        $this->getDefaultBuilder()
            ->exportNotes()
            ->generate()
        ;

        $this->assertGotenbergFormData('exportNotes', 'true');
    }

    public function testExportNotesPages(): void
    {
        $this->getDefaultBuilder()
            ->exportNotesPages()
            ->generate()
        ;

        $this->assertGotenbergFormData('exportNotesPages', 'true');
    }

    public function testExportOnlyNotesPages(): void
    {
        $this->getDefaultBuilder()
            ->exportOnlyNotesPages()
            ->generate()
        ;

        $this->assertGotenbergFormData('exportOnlyNotesPages', 'true');
    }

    public function testExportNotesInMargin(): void
    {
        $this->getDefaultBuilder()
            ->exportNotesInMargin()
            ->generate()
        ;

        $this->assertGotenbergFormData('exportNotesInMargin', 'true');
    }

    public function testConvertOooTargetToPdfTarget(): void
    {
        $this->getDefaultBuilder()
            ->convertOooTargetToPdfTarget()
            ->generate()
        ;

        $this->assertGotenbergFormData('convertOooTargetToPdfTarget', 'true');
    }

    public function testExportLinksRelativeFsys(): void
    {
        $this->getDefaultBuilder()
            ->exportLinksRelativeFsys()
            ->generate()
        ;

        $this->assertGotenbergFormData('exportLinksRelativeFsys', 'true');
    }

    public function testExportHiddenSlides(): void
    {
        $this->getDefaultBuilder()
            ->exportHiddenSlides()
            ->generate()
        ;

        $this->assertGotenbergFormData('exportHiddenSlides', 'true');
    }

    public function testSkipEmptyPages(): void
    {
        $this->getDefaultBuilder()
            ->skipEmptyPages()
            ->generate()
        ;

        $this->assertGotenbergFormData('skipEmptyPages', 'true');
    }

    public function testAddOriginalDocumentAsStream(): void
    {
        $this->getDefaultBuilder()
            ->addOriginalDocumentAsStream()
            ->generate()
        ;

        $this->assertGotenbergFormData('addOriginalDocumentAsStream', 'true');
    }

    public function testSinglePageSheets(): void
    {
        $this->getDefaultBuilder()
            ->singlePageSheets()
            ->generate()
        ;

        $this->assertGotenbergFormData('singlePageSheets', 'true');
    }

    public function testMergeTheResultingPdf(): void
    {
        $this->getDefaultBuilder()
            ->merge()
            ->generate()
        ;

        $this->assertGotenbergFormData('merge', 'true');
    }

    public function testLosslessImageCompression(): void
    {
        $this->getDefaultBuilder()
            ->losslessImageCompression()
            ->generate()
        ;

        $this->assertGotenbergFormData('losslessImageCompression', 'true');
    }

    public function testQualityOfTheJpgExport(): void
    {
        $this->getDefaultBuilder()
            ->quality(50)
            ->generate()
        ;

        $this->assertGotenbergFormData('quality', '50');
    }

    public function testReduceImageResolution(): void
    {
        $this->getDefaultBuilder()
            ->reduceImageResolution()
            ->generate()
        ;

        $this->assertGotenbergFormData('reduceImageResolution', 'true');
    }

    public function testMaxImageResolution(): void
    {
        $this->getDefaultBuilder()
            ->maxImageResolution(ImageResolutionDPI::DPI150)
            ->generate()
        ;

        $this->assertGotenbergFormData('maxImageResolution', (string) ImageResolutionDPI::DPI150->value);
    }

    public function testUnsetMaxImageResolution(): void
    {
        $builder = $this->getDefaultBuilder()
            ->maxImageResolution(ImageResolutionDPI::DPI150)
        ;

        self::assertArrayHasKey('maxImageResolution', $builder->getBodyBag()->all());

        $builder->maxImageResolution(null);
        self::assertArrayNotHasKey('maxImageResolution', $builder->getBodyBag()->all());
    }

    public function testDoNotUpdateIndexes(): void
    {
        $this->getDefaultBuilder()
            ->doNotUpdateIndexes()
            ->generate()
        ;

        $this->assertGotenbergFormData('updateIndexes', 'false');
    }
}
