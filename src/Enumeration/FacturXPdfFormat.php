<?php

namespace Sensiolabs\GotenbergBundle\Enumeration;

/**
 * PDF/A-3 variants accepted by the `/forms/pdfengines/factur-x` route.
 *
 * Distinct from {@see PdfFormat}: Factur-X requires PDF/A-3, the only PDF/A family
 * that allows embedded files, so this route rejects PDF/A-1b and PDF/A-2b.
 * Defaults to PDF/A-3b when omitted.
 */
enum FacturXPdfFormat: string
{
    case Pdf3a = 'PDF/A-3a';
    case Pdf3b = 'PDF/A-3b';
    case Pdf3u = 'PDF/A-3u';
}
