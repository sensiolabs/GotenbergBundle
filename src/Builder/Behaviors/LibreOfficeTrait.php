<?php

namespace Sensiolabs\GotenbergBundle\Builder\Behaviors;

trait LibreOfficeTrait
{
    use DownloadFromTrait;
    use LibreOffice\PagePropertiesTrait;
    use MetadataTrait;
    use PdfFormatTrait;
    use SplitTrait;
    use WebhookTrait;
}
