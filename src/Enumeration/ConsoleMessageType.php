<?php

namespace Sensiolabs\GotenbergBundle\Enumeration;

enum ConsoleMessageType: string
{
    case Log = 'log';
    case Debug = 'debug';
    case Info = 'info';
    case Warning = 'warning';
    case Error = 'error';
    case Assert = 'assert';
}
