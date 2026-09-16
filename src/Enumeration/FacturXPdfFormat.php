<?php

namespace Sensiolabs\GotenbergBundle\Enumeration;

/**
 * PDF/A-3 variants accepted by the `/forms/pdfengines/factur-x` route.
 *
 * Distinct from {@see PdfFormat}: Factur-X requires PDF/A-3, the only PDF/A family
 * that allows embedded files, and this route does not accept the 1b/2b/3b subset
 * exposed elsewhere.
 */
enum FacturXPdfFormat: string
{
    case Pdf3a = 'PDF/A-3a';
    case Pdf3b = 'PDF/A-3b';
    case Pdf3u = 'PDF/A-3u';
}
