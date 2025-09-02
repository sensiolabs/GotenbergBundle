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

class CheckPhpDoc
{
    private const BUILDERS_DIR = [
        __DIR__.'/../src/Builder/Pdf',
        __DIR__.'/../src/Builder/Screenshot',
    ];

    private const DOCS_DIR = __DIR__;

    private const README_DIR = __DIR__.'/../README.md';

    public function checkAllUrls(OutputInterface $output): void
    {
        $progressBar = new ProgressIndicator($output);
        $progressBar->start('Processing...');

        $urls = [];
        $files = $this->getFiles(self::BUILDERS_DIR, 'php');
        foreach ($files as $file) {
            array_push($urls, ...$this->extractUrls($file, 'php'));
        }

        $files = $this->getFiles([self::DOCS_DIR], 'md');
        foreach ($files as $file) {
            array_push($urls, ...$this->extractUrls($file, 'md'));
        }

        array_push($urls, ...$this->extractUrls(self::README_DIR, 'md'));
        $urls = array_unique($urls);

        foreach ($urls as $url) {
            $this->checkUrl($url);
            $progressBar->advance();
        }

        $progressBar->finish('Finished');
    }

    /**
     * @param 'php'|'md' $extension
     */
    private function getFiles(array $dir, string $extension): \Generator
    {
        foreach ($dir as $path) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

            foreach ($iterator as $file) {
                if ($file->isFile() && pathinfo($file, \PATHINFO_EXTENSION) === $extension) {
                    yield $file->getPathname();
                }
            }
        }
    }
    private function extractUrls(string $file, string $extension): array
    {
        $content = file_get_contents($file);

        match ($extension) {
            'php' => preg_match_all('/@see\s+(https?:\/\/[^\s\*]+)/', $content, $matches),
            'md' => preg_match_all('/\[[^\]]+\]\((https?:\/\/[^\s\*)]+)\)/', $content, $matches),
        };

        return $matches[1] ?? [];
    }

    private function checkUrl(string $url): void
    {
        $client = HttpClient::create();
        $response = $client->request('GET', $url);

        $crawler = new Crawler($response->getContent());
        $anchor = strstr($url, '#');

        if ($anchor) {
            if ($crawler->filter($anchor)->count() > 0 || $crawler->filter('a[name="'.str_replace('#', '', $anchor).'"]')->count() > 0) {
                return;
            }

            throw new RuntimeException("Cannot find anchor '{$anchor}' for {$url}, remove or update the link in the PHPdoc");
        }
    }
}

$application = new Application();
$application->register('check')
    ->setCode(function (OutputInterface $output, SymfonyStyle $io) {
        $checkPhpDoc = new CheckPhpDoc();

        try {
            $checkPhpDoc->checkAllUrls($output);
        } catch (RuntimeException $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }

        $io->success('All external links are valid.');

        return Command::SUCCESS;
    })
;

$application->setDefaultCommand('check');
$application->run();
