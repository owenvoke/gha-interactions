<?php

declare(strict_types=1);

namespace OwenVoke\GHAInteractions\Concerns;

use RuntimeException;

trait InteractsWithGitHubActionsAnnotations
{
    public const GH_LOG_TYPE_DEBUG = 'debug';
    public const GH_LOG_TYPE_ERROR = 'error';
    public const GH_LOG_TYPE_WARNING = 'warning';

    protected function addGitHubActionsDebugMessage(string $message): void
    {
        $this->output->writeln(
            $this->buildAnnotation(self::GH_LOG_TYPE_DEBUG, $message)
        );
    }

    protected function addGitHubActionsErrorMessage(string $message, string $file = null, int $line = null): void
    {
        $this->output->writeln(
            $this->buildAnnotation(self::GH_LOG_TYPE_ERROR, $message, $file, $line)
        );
    }

    protected function addGitHubActionsWarningMessage(string $message, string $file = null, int $line = null): void
    {
        $this->output->writeln(
            $this->buildAnnotation(self::GH_LOG_TYPE_WARNING, $message, $file, $line)
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
        if (! in_array($type, [self::GH_LOG_TYPE_DEBUG, self::GH_LOG_TYPE_ERROR, self::GH_LOG_TYPE_WARNING])) {
            throw new RuntimeException('An invalid GitHub Actions annotation type was provided');
        }

        $message = str_replace("\n", '%0A', $message);

        $fileInformation = ($file && $line) ? " file={$file},line={$line}" : null;

        return "::{$type}{$fileInformation}::{$message}";
    }
}
