<?php

namespace Sensiolabs\GotenbergBundle\Enumeration;

/**
 * Factur-X/ZUGFeRD profiles, ordered from the smallest to the richest data set. Each
 * one fixes which CII fields the embedded `factur-x.xml` is expected to carry; Gotenberg
 * only records the level in the XMP metadata and does not validate the XML against it.
 *
 * - {@see self::Minimum}: header and footer data only, equivalent to what an OCR pass extracts.
 * - {@see self::BasicWL}: the header and footer data most commonly useful to buyers, still without invoice lines.
 * - {@see self::Basic}: BASIC WL plus the essential invoice line data.
 * - {@see self::EN16931}: the full European semantic standard EN 16931 data set.
 * - {@see self::Extended}: EN 16931 plus additional data, for cross-border and multi-invoice cases.
 * - {@see self::XRechnung}: the German XRechnung CIUS of EN 16931, mandatory for German public-sector invoicing.
 *
 * @see https://fnfe-mpe.org/factur-x/ Factur-X specification and profile definitions (FNFE-MPE)
 * @see https://xeinkauf.de/xrechnung/ XRechnung specification (KoSIT)
 * @see https://gotenberg.dev/docs/manipulate-pdfs/factur-x
 */
enum FacturXConformanceLevel: string
{
    case Minimum = 'MINIMUM';
    case BasicWL = 'BASIC WL';
    case Basic = 'BASIC';
    case EN16931 = 'EN 16931';
    case Extended = 'EXTENDED';
    case XRechnung = 'XRECHNUNG';
}
