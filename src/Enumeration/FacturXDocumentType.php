<?php

namespace Sensiolabs\GotenbergBundle\Enumeration;

enum FacturXDocumentType: string
{
    case Invoice = 'INVOICE';
    case Order = 'ORDER';
    case OrderResponse = 'ORDER_RESPONSE';
    case OrderChange = 'ORDER_CHANGE';
}
