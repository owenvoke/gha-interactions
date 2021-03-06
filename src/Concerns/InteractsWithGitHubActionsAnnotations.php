<?php

declare(strict_types=1);

namespace OwenVoke\GHAInteractions\Concerns;

use RuntimeException;

trait InteractsWithGitHubActionsAnnotations
{
    protected function addGitHubActionsDebugMessage(string $message): void
    {
        $this->output->writeln(
            $this->buildAnnotation('debug', $message)
        );
    }

    protected function addGitHubActionsErrorMessage(string $message, string $file = null, int $line = null): void
    {
        $this->output->writeln(
            $this->buildAnnotation('error', $message, $file, $line)
        );
    }

    protected function addGitHubActionsWarningMessage(string $message, string $file = null, int $line = null): void
    {
        $this->output->writeln(
            $this->buildAnnotation('warning', $message, $file, $line)
        );
    }

    /** @param array<int|string, string> */
    protected function addGitHubActionsGroupedLogMessage(string $groupTitle, array $messages): void
    {
        $this->output->writeln([
            "::group::{$groupTitle}",
            ...$messages,
            '::endgroup::',
        ]);
    }

    protected function addGitHubActionsOutputParameter(string $name, string $value): void
    {
        $this->output->writeln(
            "::set-output name={$name}::{$value}"
        );
    }

    private function buildAnnotation(string $type, string $message, string $file = null, int $line = null): string
    {
        if (! in_array($type, ['debug', 'error', 'warning'])) {
            throw new RuntimeException('An invalid GitHub Actions annotation type was provided');
        }

        $message = str_replace("\n", '%0A', $message);

        $fileInformation = ($file && $line) ? " file={$file},line={$line}" : null;

        return "::{$type}{$fileInformation}::{$message}";
    }
}
