#!/usr/bin/env php
<?php

use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\FlattenPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\SplitPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\UrlScreenshotBuilder;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;

require_once \dirname(__DIR__).'/vendor/autoload.php';

/**
 * @var array<string, non-empty-list<class-string>>
 */
const BUILDERS = [
    'pdf' => [
        HtmlPdfBuilder::class,
        UrlPdfBuilder::class,
        MarkdownPdfBuilder::class,
        LibreOfficePdfBuilder::class,
        MergePdfBuilder::class,
        ConvertPdfBuilder::class,
        SplitPdfBuilder::class,
        FlattenPdfBuilder::class,
    ],
    'screenshot' => [
        HtmlScreenshotBuilder::class,
        UrlScreenshotBuilder::class,
        MarkdownScreenshotBuilder::class,
    ],
];

const EXCLUDED_METHODS = [
    '__construct',
    'setLogger',
    'setConfigurations',
    'generate',
    'generateAsync',
    'getMultipartFormData',
    'fileName',
    'processor',
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

    $lines = explode("\n", trim($rawDocComment, "\n"));
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

        $result .= $line."\n";
    }

    return $result;
}

function parseBuilder(ReflectionClass $builder): string
{
    $markdown = '';

    $builderName = $builder->getShortName();
    $markdown .= "# {$builderName}\n\n";

    $builderComment = $builder->getDocComment();

    if (false !== $builderComment) {
        $markdown .= parseDocComment($builderComment)."\n";
    }

    $methods = [];
    foreach ($builder->getInterfaces() as $interface) {
        foreach ($interface->getMethods() as $method) {
            if ('' !== ($method->getDocComment() ?: '')) {
                $methods[$method->getName()] = parseDocComment($method->getDocComment());
            }
        }
    }

    foreach ($builder->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
        if (\in_array($method->getName(), EXCLUDED_METHODS, true) === true) {
            continue;
        }

        $methodSignature = parseMethodSignature($method);
        $docComment = parseDocComment($methods[$method->getShortName()] ?? $method->getDocComment() ?: '');

        $markdown .= <<<"MARKDOWN"
        * `{$methodSignature}`:
        {$docComment}

        MARKDOWN;
    }

    return $markdown;
}

function saveFile(InputInterface $input, string $filename, string $contents): void
{
    file_put_contents($filename, $contents);
}

$application = new Application();
$application->register('generate')
    ->setCode(function (InputInterface $input) {
        $summary = "# Builders API\n\n";

        foreach (BUILDERS as $type => $builderClasses) {
            $subDirectory = "{$type}/builders_api";
            $directory = __DIR__.'/'.$subDirectory;

            if (!@mkdir($directory, recursive: true) && !is_dir($directory)) {
                throw new RuntimeException(\sprintf('Directory "%s" was not created', $directory));
            }

            $summary .= '## '.ucfirst($type)."\n\n";

            foreach ($builderClasses as $pdfBuilder) {
                $reflectionClass = new ReflectionClass($pdfBuilder);

                $markdown = parseBuilder($reflectionClass);
                saveFile($input, "{$directory}/{$reflectionClass->getShortName()}.md", $markdown);

                $summary .= "* [{$reflectionClass->getShortName()}](./{$subDirectory}/{$reflectionClass->getShortName()}.md)\n";
            }
            $summary .= "\n";

            saveFile($input, __DIR__.'/builders_api.md', $summary);
        }

        return Command::SUCCESS;
    })
;

$application->setDefaultCommand('generate');
$application->run();
