<?php

namespace Sensiolabs\GotenbergBundle\PayloadResolver\Behaviors\LibreOffice;

use Sensiolabs\GotenbergBundle\Builder\Util\ValidatorFactory;
use Symfony\Component\OptionsResolver\OptionsResolver;

trait PagePropertiesOptionsTrait
{
    abstract protected function getBodyOptionsResolver(): OptionsResolver;

    protected function configureOptions(): void
    {
        $this->getBodyOptionsResolver()
            ->define('password')
            ->info('Set the password for opening the source file.')
            ->allowedTypes('string')
        ;
        $this->getBodyOptionsResolver()
            ->define('landscape')
            ->info('Set the paper orientation to landscape.')
            ->allowedTypes('bool')
        ;
        $this->getBodyOptionsResolver()
            ->define('nativePageRanges')
            ->info("Page ranges to print, e.g., '1-5, 8, 11-13' - empty means all pages.")
            ->allowedValues(ValidatorFactory::range())
        ;
        $this->getBodyOptionsResolver()
            ->define('exportFormFields')
            ->info('Specify whether form fields are exported as widgets or only their fixed print representation is exported.')
            ->allowedTypes('bool')
        ;
        $this->getBodyOptionsResolver()
            ->define('allowDuplicateFieldNames')
            ->info('Specify whether multiple form fields exported are allowed to have the same field name.')
            ->allowedTypes('bool')
        ;
        $this->getBodyOptionsResolver()
            ->define('exportBookmarks')
            ->info('Specify if bookmarks are exported to PDF.')
            ->allowedTypes('bool')
        ;
        $this->getBodyOptionsResolver()
            ->define('exportBookmarksToPdfDestination')
            ->info('Specify that the bookmarks contained in the source LibreOffice file should be exported to the PDF file as Named Destination.')
            ->allowedTypes('bool')
        ;
        $this->getBodyOptionsResolver()
            ->define('exportPlaceholders')
            ->info('Export the placeholders fields visual markings only. The exported placeholder is ineffective.')
            ->allowedTypes('bool')
        ;
        $this->getBodyOptionsResolver()
            ->define('exportNotes')
            ->info('Specify if notes are exported to PDF.')
            ->allowedTypes('bool')
        ;
        $this->getBodyOptionsResolver()
            ->define('exportNotesPages')
            ->info('Specify if notes pages are exported to PDF. Notes pages are available in Impress documents only.')
            ->allowedTypes('bool')
        ;
        $this->getBodyOptionsResolver()
            ->define('exportOnlyNotesPages')
            ->info('Specify, if the form field exportNotesPages is set to true, if only notes pages are exported to PDF.')
            ->allowedTypes('bool')
        ;
        $this->getBodyOptionsResolver()
            ->define('exportNotesInMargin')
            ->info('Specify if notes in margin are exported to PDF.')
            ->allowedTypes('bool')
        ;
        $this->getBodyOptionsResolver()
            ->define('convertOooTargetToPdfTarget')
            ->info('Specify that the target documents with .od[tpgs] extension, will have that extension changed to .pdf when the link is exported to PDF. The source document remains untouched.')
            ->allowedTypes('bool')
        ;
        $this->getBodyOptionsResolver()
            ->define('exportLinksRelativeFsys')
            ->info('Specify that the file system related hyperlinks (file:// protocol) present in the document will be exported as relative to the source document location.')
            ->allowedTypes('bool')
        ;
        $this->getBodyOptionsResolver()
            ->define('exportHiddenSlides')
            ->info('Export, for LibreOffice Impress, slides that are not included in slide shows.')
            ->allowedTypes('bool')
        ;
        $this->getBodyOptionsResolver()
            ->define('skipEmptyPages')
            ->info('Specify that automatically inserted empty pages are suppressed. This option is active only if storing Writer documents.')
            ->allowedTypes('bool')
        ;
        $this->getBodyOptionsResolver()
            ->define('addOriginalDocumentAsStream')
            ->info('Specify that a stream is inserted to the PDF file which contains the original document for archiving purposes.')
            ->allowedTypes('bool')
        ;
        $this->getBodyOptionsResolver()
            ->define('singlePageSheets')
            ->info('Ignore each sheet’s paper size, print ranges and shown/hidden status and puts every sheet (even hidden sheets) on exactly one page.')
            ->allowedTypes('bool')
        ;
    }
}
