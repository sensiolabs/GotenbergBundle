<?php

namespace Sensiolabs\GotenbergBundle\Tests\Builder\Behaviors\LibreOffice;

use Sensiolabs\GotenbergBundle\Builder\BuilderInterface;
use Sensiolabs\GotenbergBundle\Enumeration\ImageResolutionDPI;
use Sensiolabs\GotenbergBundle\Enumeration\InitialView;
use Sensiolabs\GotenbergBundle\Enumeration\Magnification;
use Sensiolabs\GotenbergBundle\Enumeration\PageLayout;
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

    public function testWatermarkText(): void
    {
        $this->getDefaultBuilder()
            ->watermarkText('CONFIDENTIAL')
            ->generate()
        ;

        $this->assertGotenbergFormData('nativeWatermarkText', 'CONFIDENTIAL');
    }

    public function testWatermarkColor(): void
    {
        $this->getDefaultBuilder()
            ->watermarkColor('#FF0000')
            ->generate()
        ;

        $this->assertGotenbergFormData('nativeWatermarkColor', '16711680');
    }

    public function testWatermarkFontHeight(): void
    {
        $this->getDefaultBuilder()
            ->watermarkFontHeight(50)
            ->generate()
        ;

        $this->assertGotenbergFormData('nativeWatermarkFontHeight', '50');
    }

    public function testWatermarkRotateAngle(): void
    {
        $this->getDefaultBuilder()
            ->watermarkRotateAngle(-450)
            ->generate()
        ;

        $this->assertGotenbergFormData('nativeWatermarkRotateAngle', '-450');
    }

    public function testWatermarkFontName(): void
    {
        $this->getDefaultBuilder()
            ->watermarkFontName('Liberation Sans')
            ->generate()
        ;

        $this->assertGotenbergFormData('nativeWatermarkFontName', 'Liberation Sans');
    }

    public function testTiledWatermarkText(): void
    {
        $this->getDefaultBuilder()
            ->tiledWatermarkText('DRAFT')
            ->generate()
        ;

        $this->assertGotenbergFormData('nativeTiledWatermarkText', 'DRAFT');
    }

    public function testInitialView(): void
    {
        $this->getDefaultBuilder()
            ->initialView(InitialView::Thumbnails)
            ->generate()
        ;

        $this->assertGotenbergFormData('initialView', (string) InitialView::Thumbnails->value);
    }

    public function testUnsetInitialView(): void
    {
        $builder = $this->getDefaultBuilder()
            ->initialView(InitialView::Thumbnails)
        ;

        self::assertArrayHasKey('initialView', $builder->getBodyBag()->all());

        $builder->initialView(null);
        self::assertArrayNotHasKey('initialView', $builder->getBodyBag()->all());
    }

    public function testInitialPage(): void
    {
        $this->getDefaultBuilder()
            ->initialPage(10)
            ->generate()
        ;

        $this->assertGotenbergFormData('initialPage', '10');
    }

    public function testUnsetInitialPage(): void
    {
        $builder = $this->getDefaultBuilder()
            ->initialPage(10)
        ;

        self::assertArrayHasKey('initialPage', $builder->getBodyBag()->all());

        $builder->initialPage(null);
        self::assertArrayNotHasKey('initialPage', $builder->getBodyBag()->all());
    }

    public function testMagnification(): void
    {
        $this->getDefaultBuilder()
            ->magnification(Magnification::FitVisible)
            ->generate()
        ;

        $this->assertGotenbergFormData('magnification', (string) Magnification::FitVisible->value);
    }

    public function testUnsetMagnification(): void
    {
        $builder = $this->getDefaultBuilder()
            ->magnification(Magnification::FitVisible)
        ;

        self::assertArrayHasKey('magnification', $builder->getBodyBag()->all());

        $builder->magnification(null);
        self::assertArrayNotHasKey('magnification', $builder->getBodyBag()->all());
    }

    public function testZoom(): void
    {
        $this->getDefaultBuilder()
            ->magnification(Magnification::UseZoomValue)
            ->zoom(50)
            ->generate()
        ;

        $this->assertGotenbergFormData('zoom', '50');
    }

    public function testUnsetZoom(): void
    {
        $builder = $this->getDefaultBuilder()
            ->magnification(Magnification::UseZoomValue)
            ->zoom(50);

        self::assertArrayHasKey('zoom', $builder->getBodyBag()->all());

        $builder->zoom(null);
        self::assertArrayNotHasKey('zoom', $builder->getBodyBag()->all());
    }

    public function testPageLayout(): void
    {
        $this->getDefaultBuilder()
            ->pageLayout(PageLayout::SinglePage)
            ->generate()
        ;

        $this->assertGotenbergFormData('pageLayout', (string) PageLayout::SinglePage->value);
    }

    public function testUnsetPageLayout(): void
    {
        $builder = $this->getDefaultBuilder()
            ->pageLayout(PageLayout::SinglePage);

        self::assertArrayHasKey('pageLayout', $builder->getBodyBag()->all());

        $builder->pageLayout(null);
        self::assertArrayNotHasKey('pageLayout', $builder->getBodyBag()->all());
    }

    public function testFirstPageOnLeft(): void
    {
        $this->getDefaultBuilder()
            ->firstPageOnLeft()
            ->generate()
        ;

        $this->assertGotenbergFormData('firstPageOnLeft', 'true');
    }

    public function testUnsetFirstPageOnLeft(): void
    {
        $builder = $this->getDefaultBuilder()
            ->firstPageOnLeft();

        self::assertArrayHasKey('firstPageOnLeft', $builder->getBodyBag()->all());

        $builder->firstPageOnLeft(false);
        self::assertArrayNotHasKey('firstPageOnLeft', $builder->getBodyBag()->all());
    }

    public function testResizeWindowToInitialPage(): void
    {
        $this->getDefaultBuilder()
            ->resizeWindowToInitialPage()
            ->generate()
        ;

        $this->assertGotenbergFormData('resizeWindowToInitialPage', 'true');
    }

    public function testUnsetResizeWindowToInitialPage(): void
    {
        $builder = $this->getDefaultBuilder()
            ->resizeWindowToInitialPage();

        self::assertArrayHasKey('resizeWindowToInitialPage', $builder->getBodyBag()->all());

        $builder->resizeWindowToInitialPage(false);
        self::assertArrayNotHasKey('resizeWindowToInitialPage', $builder->getBodyBag()->all());
    }

    public function testCenterWindow(): void
    {
        $this->getDefaultBuilder()
            ->centerWindow()
            ->generate()
        ;

        $this->assertGotenbergFormData('centerWindow', 'true');
    }

    public function testUnsetCenterWindow(): void
    {
        $builder = $this->getDefaultBuilder()
            ->centerWindow();

        self::assertArrayHasKey('centerWindow', $builder->getBodyBag()->all());

        $builder->centerWindow(false);
        self::assertArrayNotHasKey('centerWindow', $builder->getBodyBag()->all());
    }

    public function testOpenInFullScreenMode(): void
    {
        $this->getDefaultBuilder()
            ->openInFullScreenMode()
            ->generate()
        ;

        $this->assertGotenbergFormData('openInFullScreenMode', 'true');
    }

    public function testUnsetOpenInFullScreenMode(): void
    {
        $builder = $this->getDefaultBuilder()
            ->openInFullScreenMode();

        self::assertArrayHasKey('openInFullScreenMode', $builder->getBodyBag()->all());

        $builder->openInFullScreenMode(false);
        self::assertArrayNotHasKey('openInFullScreenMode', $builder->getBodyBag()->all());
    }

    public function testDisplayPDFDocumentTitle(): void
    {
        $this->getDefaultBuilder()
            ->displayPDFDocumentTitle()
            ->generate()
        ;

        $this->assertGotenbergFormData('displayPDFDocumentTitle', 'true');
    }

    public function testUnsetDisplayPDFDocumentTitle(): void
    {
        $builder = $this->getDefaultBuilder()
            ->displayPDFDocumentTitle();

        self::assertArrayHasKey('displayPDFDocumentTitle', $builder->getBodyBag()->all());

        $builder->displayPDFDocumentTitle(false);
        self::assertArrayNotHasKey('displayPDFDocumentTitle', $builder->getBodyBag()->all());
    }

    public function testHideViewerMenubar(): void
    {
        $this->getDefaultBuilder()
            ->hideViewerMenubar()
            ->generate()
        ;

        $this->assertGotenbergFormData('hideViewerMenubar', 'true');
    }

    public function testUnsetHideViewerMenubar(): void
    {
        $builder = $this->getDefaultBuilder()
            ->hideViewerMenubar();

        self::assertArrayHasKey('hideViewerMenubar', $builder->getBodyBag()->all());

        $builder->hideViewerMenubar(false);
        self::assertArrayNotHasKey('hideViewerMenubar', $builder->getBodyBag()->all());
    }

    public function testHideViewerToolbar(): void
    {
        $this->getDefaultBuilder()
            ->hideViewerToolbar()
            ->generate()
        ;

        $this->assertGotenbergFormData('hideViewerToolbar', 'true');
    }

    public function testUnsetHideViewerToolbar(): void
    {
        $builder = $this->getDefaultBuilder()
            ->hideViewerToolbar();

        self::assertArrayHasKey('hideViewerToolbar', $builder->getBodyBag()->all());

        $builder->hideViewerToolbar(false);
        self::assertArrayNotHasKey('hideViewerToolbar', $builder->getBodyBag()->all());
    }

    public function testHideViewerWindowControls(): void
    {
        $this->getDefaultBuilder()
            ->hideViewerWindowControls()
            ->generate()
        ;

        $this->assertGotenbergFormData('hideViewerWindowControls', 'true');
    }

    public function testUnsetHideViewerWindowControls(): void
    {
        $builder = $this->getDefaultBuilder()
            ->hideViewerWindowControls();

        self::assertArrayHasKey('hideViewerWindowControls', $builder->getBodyBag()->all());

        $builder->hideViewerWindowControls(false);
        self::assertArrayNotHasKey('hideViewerWindowControls', $builder->getBodyBag()->all());
    }

    public function testUseTransitionEffects(): void
    {
        $this->getDefaultBuilder()
            ->useTransitionEffects()
            ->generate()
        ;

        $this->assertGotenbergFormData('useTransitionEffects', 'true');
    }

    public function testUnsetUseTransitionEffects(): void
    {
        $builder = $this->getDefaultBuilder()
            ->useTransitionEffects();

        self::assertArrayHasKey('useTransitionEffects', $builder->getBodyBag()->all());

        $builder->useTransitionEffects(false);
        self::assertArrayNotHasKey('useTransitionEffects', $builder->getBodyBag()->all());
    }

    public function testOpenBookmarkLevels(): void
    {
        $this->getDefaultBuilder()
            ->openBookmarkLevels(-1)
            ->generate()
        ;

        $this->assertGotenbergFormData('openBookmarkLevels', '-1');
    }

    public function testUnsetOpenBookmarkLevels(): void
    {
        $builder = $this->getDefaultBuilder()
            ->openBookmarkLevels(-1);

        self::assertArrayHasKey('openBookmarkLevels', $builder->getBodyBag()->all());

        $builder->openBookmarkLevels(null);
        self::assertArrayNotHasKey('openBookmarkLevels', $builder->getBodyBag()->all());
    }
}
