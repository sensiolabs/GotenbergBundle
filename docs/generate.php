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

class Summary
{
    /**
     * @var array<string, array<string, ReflectionClass<PdfBuilderInterface|ScreenshotBuilderInterface>>>
     */
    private array $builders = [];

    /**
     * @var array<string, string>
     */
    private array $filenames = [];

    /**
     * @param ReflectionClass<PdfBuilderInterface|ScreenshotBuilderInterface> $class
     */
    public function register(string $type, ReflectionClass $class): void
    {
        $this->builders[$type] ??= [];
        $this->builders[$type][$class->getShortName()] = $class;

        $this->filenames[$class->getName()] = "{$type}/builders_api/{$class->getShortName()}.md";
    }

    public function extract(): string
    {
        $summary = "# Builders API\n\n";
        ksort($this->builders);

        foreach ($this->builders as $type => $builders) {
            $summary .= '## '.ucfirst($type)."\n\n";
            ksort($builders);

            foreach ($builders as $builder) {
                $summary .= "* [{$builder->getShortName()}](./{$this->getFilename($builder->getName())})\n";
            }
            $summary .= "\n";
        }

        return $summary;
    }

    public function getFilename(string $className): string
    {
        return $this->filenames[$className];
    }
}

/**
 * @phpstan-type ParsedDocBlock array{package: string|null, description: list<string>, tags: array{param: array<string, array{type: string, description: string}>, see: list<string>, example: list<string>}}
 */
class BuilderParser
{
    /**
     * @var array<string, non-empty-list<class-string>>
     */
    public const BUILDERS = [
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

    private const EXCLUDED_METHODS = [
        '__construct',
        'setLogger',
        'setConfigurations',
        'generate',
        'generateAsync',
        'fileName',
        'processor',
        'type',
        'getBodyBag',
        'getHeaderBag',
    ];

    private string $name;

    /**
     * @var array{
     *     '@'?: ParsedDocBlock,
     *     'methods': array<string, array<string, ParsedDocBlock>>,
     * }
     */
    private array $parts = [
        'methods' => [],
    ];

    /**
     * @var array<string, string>
     */
    private array $methodsSignature = [];

    /**
     * @param class-string<PdfBuilderInterface|ScreenshotBuilderInterface> $builder
     */
    public function prepare(Summary $summary, string $type, string $builder): void
    {
        $class = new ReflectionClass($builder);
        $summary->register($type, $class);

        $this->name = $class->getShortName();
        $this->prepareBuilder($class);
    }

    public function extract(): string
    {
        $markdown = "# {$this->name}\n\n";
        $renderDescription = static fn (array $parts) => trim(implode('<br />', $parts), "\ \n\r\t\v\0");

        /**
         * @param list<string> $seeList
         */
        $renderSee = static function (array $seeList): string {
            if ([] === $seeList) {
                return '';
            }

            $lastKey = array_key_last($seeList);

            $markdown = '> [!TIP]';
            foreach ($seeList as $key => $see) {
                $markdown .= "\n> See: [{$see}]({$see})";

                $isLast = $lastKey === $key;

                if ($isLast === false) {
                    $markdown .= '<br />';
                }
            }

            return rtrim($markdown, "<br />");
        };

        /**
         * @param ParsedDocBlock $parts
         */
        $renderParts = static function (array $parts) use ($renderDescription, $renderSee): string {
            $markdown = '';

            $description = $renderDescription($parts['description']);
            if ('' !== $description) {
                $markdown .= $description."\n";
            }

            $see = $renderSee($parts['tags']['see'] ?? []);
            if ('' !== $see) {
                if ('' !== $markdown) {
                    $markdown .= "\n";
                }

                $markdown .= $see."\n";
            }

            return $markdown;
        };

        if (isset($this->parts['@'])) {
            $markdown .= $renderParts($this->parts['@']);
            $markdown .= "\n";
        }

        uksort($this->parts['methods'], static function ($a, $b) {
            if ('@' === $a) {
                return -1;
            }

            if ('@' === $b) {
                return +1;
            }

            return strcmp($a, $b);
        });

        foreach ($this->parts['methods'] as $package => $methods) {
            ksort($methods);

            foreach ($methods as $methodName => $parts) {
                $markdown .= "### {$this->methodsSignature[$methodName]}";

                $renderedParts = $renderParts($parts);
                if ('' !== $renderedParts) {
                    $markdown .= "\n{$renderedParts}";
                }

                $markdown .= "\n";
            }
        }

        return $markdown;
    }

    /**
     * @param ReflectionClass<PdfBuilderInterface|ScreenshotBuilderInterface> $class
     */
    private function prepareBuilder(ReflectionClass $class): void
    {
        $this->parts = [
            'methods' => [],
        ];

        $classDocComment = $class->getDocComment() ?: '';
        if ('' !== $classDocComment) {
            $this->parts['@'] = $this->parsePhpDoc($classDocComment);
        }

        $this->prepareBuilderFromClass($class);

        foreach ($this->parts['methods']['@'] as $methodName => $parts) {
            $package = $parts['package'] ?? '@';

            if ('@' !== $package) {
                $this->parts['methods'][$package][$methodName] = $parts;
                unset($this->parts['methods']['@'][$methodName]);
            }
        }
    }

    /**
     * @param ReflectionClass<object> $class
     */
    private function prepareBuilderFromClass(ReflectionClass $class): void
    {
        $parentClass = $class->getParentClass();

        if (false !== $parentClass) {
            $this->prepareBuilderFromClass($parentClass);
        }

        foreach ($class->getInterfaces() as $interface) {
            $this->prepareBuilderFromClass($interface);
        }

        foreach ($class->getTraits() as $trait) {
            $this->prepareBuilderFromClass($trait);
        }

        $defaultPackage = $this->parsePhpDoc($class->getDocComment() ?: '')['package'] ?? null;

        foreach ($class->getMethods(ReflectionMethod::IS_PUBLIC) as $method) {
            if (\in_array($method->getName(), self::EXCLUDED_METHODS, true) === true) {
                continue;
            }

            $this->methodsSignature[$method->getName()] = $this->parseMethodSignature($method);

            $methodDocComment = $method->getDocComment() ?: '';
            $this->parts['methods']['@'][$method->getShortName()] ??= [];

            $parsedDocBlock = $this->parsePhpDoc($methodDocComment);
            $parsedDocBlock['package'] ??= $defaultPackage;

            if ([] === ($this->parts['methods']['@'][$method->getShortName()] ?? [])) {
                $this->parts['methods']['@'][$method->getShortName()] = $parsedDocBlock;

                continue;
            }

            $currentPackage = $this->parts['methods']['@'][$method->getShortName()]['package'] ?? '@';
            $newPackage = $parsedDocBlock['package'];

            if (null !== $newPackage && $currentPackage !== $newPackage) {
                $this->parts['methods']['@'][$method->getShortName()]['package'] = $newPackage;
            }

            if (isset($parsedDocBlock['description']) && [''] !== $parsedDocBlock['description']) {
                $this->parts['methods']['@'][$method->getShortName()]['description'] = $parsedDocBlock['description'];
            }

            if (isset($parsedDocBlock['tags']['param']) && [] !== $parsedDocBlock['tags']['param']) {
                $this->parts['methods']['@'][$method->getShortName()]['tags']['param'] = $parsedDocBlock['tags']['param'] + $this->parts['methods']['@'][$method->getShortName()]['tags']['param'];
            }

            if (isset($parsedDocBlock['tags']['see'])) {
                $this->parts['methods']['@'][$method->getShortName()]['tags']['see'] = array_unique(array_merge(
                    $this->parts['methods']['@'][$method->getShortName()]['tags']['see'],
                    $parsedDocBlock['tags']['see'],
                ));
            }
        }
    }

    /**
     * @return ParsedDocBlock
     *
     * @throws LogicException
     */
    private function parsePhpDoc(string $rawPhpDoc): array
    {
        $lines = preg_split("/\r\n|\n|\r/", trim($rawPhpDoc, "/** \t\n\r"));

        if (false === $lines) {
            throw new LogicException('Unable to parse doc comment.');
        }

        $description = [];
        $tags = [
            'param' => [],
        ];
        $currentPackage = null;
        $currentTag = null;
        $currentParam = null;

        foreach ($lines as $line) {
            $line = trim($line, ' *');

            if (str_starts_with($line, '@')) {
                $tagFound = preg_match('/^@(\S+)\s*(.*)$/', $line, $matches);
                if (false !== $tagFound && \count($matches) > 0) {
                    $currentTag = $matches[1];
                    $value = $matches[2] ?? '';

                    if ('package' === $currentTag) {
                        $currentPackage = $value;
                        $currentTag = null;
                    } elseif ('param' === $currentTag) {
                        $paramTagFound = preg_match('/^(\S+)\s+(\$\S+)\s*(.*)$/', $value, $paramMatches);
                        if (false !== $paramTagFound && \count($paramMatches) > 0) {
                            [$type, $name, $desc] = $paramMatches;

                            $tags['param'][$name] = [
                                'type' => $type,
                                'description' => $desc,
                            ];
                            $currentParam = $name;
                        }
                    } else {
                        $tags[$currentTag][] = $value;
                        $currentParam = null;
                    }
                }
            } elseif ('param' === $currentTag && null !== $currentParam) {
                $tags['param'][$currentParam]['description'] .= ' '.$line;
            } elseif (null !== $currentTag) {
                if (null === array_key_last($tags[$currentTag])) {
                    $tags[$currentTag][] = $line;
                } else {
                    $tags[$currentTag][array_key_last($tags[$currentTag])] .= ' '.$line;
                }
            } else {
                $description[] = $line;
            }
        }

        return [
            'package' => $currentPackage,
            'description' => $description,
            'tags' => $tags,
        ];
    }

    public function parseMethodSignature(ReflectionMethod $method): string
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
}

$application = new Application();
$application->register('generate')
    ->setCode(function (InputInterface $input) {
        $summary = new Summary();

        $buildersByType = BuilderParser::BUILDERS;
        krsort($buildersByType);

        foreach ($buildersByType as $type => $builders) {
            krsort($builders);
            foreach ($builders as $builder) {
                $builderParser = new BuilderParser();
                $builderParser->prepare($summary, $type, $builder);

                $filename = $summary->getFilename($builder);
                $directory = __DIR__.'/'.\dirname($filename);

                if (!@mkdir($directory, recursive: true) && !is_dir($directory)) {
                    throw new RuntimeException(\sprintf('Directory "%s" was not created', $directory));
                }

                file_put_contents(__DIR__.'/'.$filename, $builderParser->extract());
            }
        }
        file_put_contents(__DIR__.'/builders_api.md', $summary->extract());

        return Command::SUCCESS;
    })
;

$application->setDefaultCommand('generate');
$application->run();
