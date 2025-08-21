#!/usr/bin/env php
<?php

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressIndicator;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

require_once \dirname(__DIR__).'/vendor/autoload.php';

class CheckPhpDoc
{
    public function checkAllSeeUrls(array $dirs, OutputInterface $output): void
    {
        $files = $this->getPhpFiles($dirs);

        $progressBar = new ProgressIndicator($output);
        $progressBar->start('Processing...');

        foreach ($files as $file) {
            $urls = $this->extractSeeUrls($file);

            foreach ($urls as $url) {
                $this->checkUrl($url);
                $progressBar->advance();
            }
        }

        $progressBar->finish('Finished');
    }

    private function getPhpFiles(array $paths): array
    {
        $files = [];

        foreach ($paths as $path) {
            $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

            foreach ($iterator as $file) {
                if ($file->isFile() && pathinfo($file, \PATHINFO_EXTENSION) === 'php') {
                    $files[] = $file->getPathname();
                }
            }
        }

        return $files;
    }

    private function extractSeeUrls(string $file): array
    {
        $content = file_get_contents($file);
        preg_match_all('/@see\s+(https?:\/\/[^\s\*]+)/', $content, $matches);

        return $matches[1] ?? [];
    }

    private function checkUrl(string $url): void
    {
        $externalDoc = @file_get_contents($url);
        if (!$externalDoc) {
            throw new RuntimeException('Unable to read document');
        }

        $anchor = strstr($url, '#');
        if ($anchor) {
            $id = str_replace('#', '', $anchor);

            if (str_contains($externalDoc, 'id="'.$id.'"') || str_contains($externalDoc, "name='{$id}'")) {
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

        $buildersDir = [
            __DIR__.'/../src/Builder/Pdf',
            __DIR__.'/../src/Builder/Screenshot',
        ];

        try {
            $checkPhpDoc->checkAllSeeUrls($buildersDir, $output);
        } catch (RuntimeException $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }

        $io->success('All external docs are availble.');

        return Command::SUCCESS;
    })
;

$application->setDefaultCommand('check');
$application->run();
