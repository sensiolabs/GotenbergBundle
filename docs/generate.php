#!/usr/bin/env php
<?php

use Sensiolabs\GotenbergBundle\Builder\Pdf\ConvertPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\HtmlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\LibreOfficePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MarkdownPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\MergePdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\PdfBuilderInterface;
use Sensiolabs\GotenbergBundle\Builder\Pdf\SplitPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Pdf\UrlPdfBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\HtmlScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\MarkdownScreenshotBuilder;
use Sensiolabs\GotenbergBundle\Builder\Screenshot\ScreenshotBuilderInterface;
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
    $lines = preg_split("/\r\n|\n|\r/", trim($rawDocComment, "/** \t\n\r"));

    if (false === $lines) {
        throw new LogicException('Unable to parse doc comment.');
    }

    $description = [];
    $tags = [
        'param' => [],
    ];
    $currentTag = null;
    $currentParam = null;

    foreach ($lines as $line) {
        $line = trim($line, " *");

        if (str_starts_with($line, '@')) {
            $tagFound = preg_match('/^@(\S+)\s*(.*)$/', $line, $matches);
            if (false !== $tagFound && count($matches) > 0) {
                $currentTag = $matches[1];
                $value = $matches[2] ?? '';

                if ($currentTag === 'param') {
                    $paramTagFound = preg_match('/^(\S+)\s+(\$\S+)\s*(.*)$/', $value, $paramMatches);
                    if (false !== $paramTagFound && count($paramMatches) > 0) {
                        [$type, $name, $desc] = $paramMatches;

                        $tags['param'][$name] = [
                            'type' => $type,
                            'description' => $desc
                        ];
                        $currentParam = $name;
                    }
                } else {
                    $tags[$currentTag][] = $value;
                    $currentParam = null;
                }
            }
        } elseif ($currentTag === 'param' && $currentParam !== null) {
            $tags['param'][$currentParam]['description'] .= ' ' . $line;
        } elseif ($currentTag !== null) {
            if (null === array_key_last($tags[$currentTag])) {
                $tags[$currentTag][] = $line;
            } else {
                $tags[$currentTag][array_key_last($tags[$currentTag])] .= ' ' . $line;
            }
        } else {
            $description[] = $line;
        }
    }

    $description = implode("\n", $description);

    $tags['see'] ??= [];

    $description = trim($description, "\ \n\r\t\v\0") . "\n";

    if (count($tags['see']) > 0) {
        $description .= "\n";
        $description .= "> [!TIP]";
    }

    foreach ($tags['see'] as $see) {
        $description .= "\n> See: [{$see}]({$see})";
    }


    if (count($tags['see']) > 0) {
        $description .= "\n";
    }

    if (trim($description, "\ \n\r\t\v\0") === '') {
        return '';
    }

    return $description;
}

/**
 * @param ReflectionClass<PdfBuilderInterface|ScreenshotBuilderInterface> $builder
 */
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
            $methodDocComment = $method->getDocComment() ?: '';
            if ('' !== $methodDocComment) {
                $methods[$method->getName()] = parseDocComment($methodDocComment);
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
