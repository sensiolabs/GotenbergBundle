#!/usr/bin/env php
<?php

use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;

require_once dirname(__DIR__).'/vendor/autoload.php';

define('EXCLUDED_METHODS', [
    '__construct',
    'setLogger',
    'setConfigurations',
    'generate',
    'getMultipartFormData',
]);

/** @var array<string, non-empty-list<class-string>> $builders */
$builders = [
    'Pdf' => [
        HtmlPdfBuilder::class,
        UrlPdfBuilder::class,
        MarkdownPdfBuilder::class,
        LibreOfficePdfBuilder::class,
    ],
    'Screenshot' => [
        HtmlScreenshotBuilder::class,
        UrlScreenshotBuilder::class,
        MarkdownScreenshotBuilder::class,
    ],
];

function parseMethodSignature(ReflectionMethod $method): string
{
    $methodName = $method->getName();

    $parameters = [];

    foreach ($method->getParameters() as $parameter) {
        $parameterName = $parameter->getName();
        $parameterType = $parameter->getType();

        $parameters[] = "{$parameterType} \${$parameterName}";
    }

    return $methodName.'('.implode(', ', $parameters).')';
}

function parseDocComment(string $rawDocComment): string
{
    $result = '';

    $lines = explode("\n", $rawDocComment);
    array_shift($lines);
    array_pop($lines);

    foreach ($lines as $line) {
        $line = trim($line);
        $line = ltrim($line, '*');
        $line = ltrim($line);

        if ('' === $line) {
            continue;
        }

        if (str_starts_with($line, '@')) {
            continue;
        }

        $result .= $line . "\n";
    }

    return $result;
}

/**
 * @param class-string $builder
 */
function parseBuilder(ReflectionClass $builder): string
{
    $markdown = '';

    $builderName = $builder->getShortName();
    $markdown .= "{$builderName}\n";
    $markdown .= str_repeat('=', strlen($builderName)) . "\n\n";

    foreach ($builder->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        if (in_array($method->getName(), EXCLUDED_METHODS, true) === true) {
            continue;
        }

        $methodSignature = parseMethodSignature($method);
        $docComment = parseDocComment($method->getDocComment());

        $markdown .= <<<"MARKDOWN"
        * `{$methodSignature}`: 
        {$docComment}
        
        MARKDOWN;

    }

    return $markdown;
}

$summary = <<<MARKDOWN
Builders
========

MARKDOWN;

foreach ($builders as $type => $builderClasses) {
    $directory = __DIR__."/{$type}";

    if (!@mkdir($directory, recursive: true) && !is_dir($directory)) {
        throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
    }

    $summary .= "# {$type}\n\n";

    foreach ($builderClasses as $pdfBuilder) {
        $reflectionClass = new ReflectionClass($pdfBuilder);

        $markdown = parseBuilder($reflectionClass);
        file_put_contents("{$directory}/{$reflectionClass->getShortName()}.md", $markdown);

        $summary .= "* [{$reflectionClass->getShortName()}](./{$type}/{$reflectionClass->getShortName()}.md)\n";
    }
    $summary .= "\n";

    file_put_contents(__DIR__ . '/Builders.md', $summary);
}
