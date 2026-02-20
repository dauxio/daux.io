<?php

declare(strict_types=1);

namespace Todaymade\Daux\Format\Confluence;

use Symfony\Component\Process\Process;
use Todaymade\Daux\Config;
use Todaymade\Daux\Exception;
use Todaymade\Daux\Tree\ComputedRaw;
use Todaymade\Daux\Tree\Directory;

class MermaidCliRenderer implements MermaidRendererInterface
{
    public function render(string $mermaidCode, string $diagramId, Config $config, Directory $parent): ComputedRaw
    {
        $confluenceConfig = $config->getConfluenceConfiguration();
        $cliPath = $confluenceConfig->getMermaidCliPath();
        $format = $confluenceConfig->getMermaidImageFormat();

        $tempDir = sys_get_temp_dir();
        $inputFile = $tempDir . '/' . $diagramId . '.mmd';
        $outputFile = $tempDir . '/' . $diagramId . '.' . $format;

        try {
            // Write Mermaid code to temporary file
            file_put_contents($inputFile, $mermaidCode);

            // Execute mermaid-cli
            // Handle npx command
            if (str_starts_with($cliPath, 'npx ')) {
                // For npx, split the command
                $commandParts = explode(' ', $cliPath);
                $command = array_merge($commandParts, [
                    '-i',
                    $inputFile,
                    '-o',
                    $outputFile,
                    '-e',
                    $format,
                ]);
            } else {
                $command = [
                    $cliPath,
                    '-i',
                    $inputFile,
                    '-o',
                    $outputFile,
                    '-e',
                    $format,
                ];
            }

            $process = new Process($command);
            $process->setTimeout(30);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new Exception('Failed to render Mermaid diagram: ' . $process->getErrorOutput());
            }

            if (!file_exists($outputFile)) {
                throw new Exception('Mermaid rendering failed: output file was not created');
            }

            // Read rendered image
            $imageContent = file_get_contents($outputFile);

            // Create ComputedRaw attachment with parent directory
            $filename = $diagramId . '.' . $format;
            $computedRaw = new ComputedRaw($parent, $filename);
            $computedRaw->setContent($imageContent);

            return $computedRaw;
        } finally {
            // Cleanup temporary files
            if (file_exists($inputFile)) {
                @unlink($inputFile);
            }
            if (file_exists($outputFile)) {
                @unlink($outputFile);
            }
        }
    }

    public function isAvailable(Config $config): bool
    {
        $confluenceConfig = $config->getConfluenceConfiguration();
        $cliPath = $confluenceConfig->getMermaidCliPath();

        // Handle npx command
        if (str_starts_with($cliPath, 'npx ')) {
            // For npx, we can't easily check version, so we'll try to run it
            // and catch errors during actual rendering
            return true;
        }

        $process = new Process([$cliPath, '--version']);
        $process->run();

        return $process->isSuccessful();
    }

    public function getName(): string
    {
        return 'mermaid-cli';
    }
}
