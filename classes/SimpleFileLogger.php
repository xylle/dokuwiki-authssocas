<?php

namespace AuthSSOCas;

use Psr\Log\AbstractLogger;

class SimpleFileLogger extends AbstractLogger
{
    private string $file;

    public function __construct(string $file)
    {
        $this->file = $file;
    }

    public function log($level, $message, array $context = []): void
    {
        $line = sprintf(
            "[%s] %s: %s\n",
            date('Y-m-d H:i:s'),
            strtoupper($level),
            $this->interpolate($message, $context)
        );
        file_put_contents($this->file, $line, FILE_APPEND);
    }

    private function interpolate(string $message, array $context): string
    {
        foreach ($context as $key => $val) {
            $message = str_replace("{{$key}}", $val, $message);
        }
        return $message;
    }
}
