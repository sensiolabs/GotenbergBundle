#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressIndicator;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;

require_once \dirname(__DIR__).'/vendor/autoload.php';

class ValidateUrlDoc
{
    private const AVAILABLE_EXTENSIONS = ['php', 'md'];

    private const DIR_AND_FILES_TO_CHECK = [
        __DIR__.'/../src/Builder/Pdf',
        __DIR__.'/../src/Builder/Screenshot',
        __DIR__,
        __DIR__.'/../README.md',
        __DIR__.'/../src/DependencyInjection/Configuration.php',
    ];

    public function validate(OutputInterface $output, SymfonyStyle $io): int
    {
        $progressBar = new ProgressIndicator($output);
        $progressBar->start('Processing...');

        $urls = [];
        $files = $this->getFiles(self::DIR_AND_FILES_TO_CHECK);
        foreach ($files as $file) {
            array_push($urls, ...$this->extractUrls($file));
        }

        $urls = array_unique($urls);

        $client = HttpClient::create();
        $allResponses = [];
        foreach ($urls as $url) {
            $allResponses[] = $client->request('GET', $url);
        }

        $urlsWithErrors = [];
        foreach ($client->stream($allResponses) as $response => $chunk) {
            if ($chunk->isLast()) {
                $statusCode = $response->getStatusCode();
                $url = $response->getInfo('url');

                if (200 !== $statusCode) {
                    $urlsWithErrors[] = "HTTP {$statusCode} error for: {$url}";
                    continue;
                }

                $checkError = $this->checkContentResponse($response->getInfo('url'), $response->getContent());
                if (\is_string($checkError)) {
                    $urlsWithErrors[] = $checkError;
                }

                $progressBar->advance();
            }
        }

        if (\count($urlsWithErrors) > 0) {
            $io->error($urlsWithErrors);

            return Command::FAILURE;
        }

        $progressBar->finish('Finished');
        $io->success('All external links are valid.');

        return Command::SUCCESS;
    }

    private function getFiles(array $paths): Generator
    {
        foreach ($paths as $path) {
            if (is_file($path)) {
                $splInfo = new SplFileInfo($path);
                if (!\in_array($splInfo->getExtension(), self::AVAILABLE_EXTENSIONS, true)) {
                    throw new RuntimeException(\sprintf('File "%s" with "%s" extension is not allowed.', $splInfo->getFilename(), $splInfo->getExtension()));
                }

                yield $splInfo;
                continue;
            }

            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));
            foreach ($iterator as $file) {
                if ($file->isFile() && \in_array(pathinfo($file, \PATHINFO_EXTENSION), self::AVAILABLE_EXTENSIONS, true)) {
                    yield $file;
                }
            }
        }
    }

    private function extractUrls(SplFileInfo $file): array
    {
        $content = file_get_contents($file->getPathname());

        // https://regex101.com/r/t5uiUp/1
        // https://regex101.com/r/p62cij/1
        match ($file->getExtension()) {
            'php' => preg_match_all('/(?:@see\s+|->info\([^)]*)(https?:\/\/[^\s\'")]+)(?:[^)]*\))?/', $content, $matches),
            'md' => preg_match_all('/\[[^\]]+\]\((https?:\/\/[^\s\*)]+)\)/', $content, $matches),
        };

        return $matches[1] ?? [];
    }

    private function checkContentResponse(string $url, string $content): string|null
    {
        $crawler = new Crawler($content);
        $parsedUrl = parse_url($url);

        if (\array_key_exists('fragment', $parsedUrl)) {
            $fragment = $parsedUrl['fragment'];
            if ($crawler->filter('#'.$fragment)->count() > 0 || $crawler->filter('a[name="'.$fragment.'"]')->count() > 0) {
                return null;
            }

            return \sprintf('Cannot find anchor "%s" for "%s", remove or update the link in the PHPdoc', $fragment, $url);
        }

        return null;
    }
}

$application = new Application();
$application->register('check')
    ->setCode(function (OutputInterface $output, SymfonyStyle $io) {
        return (new ValidateUrlDoc())->validate($output, $io);
    })
;

$application->setDefaultCommand('check');
$application->run();
