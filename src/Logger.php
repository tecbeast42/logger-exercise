<?php

namespace Tecbeast42\LoggerExample;

use Exception;
use League\Flysystem\Filesystem;

class Logger {

    // if the lib is only used with newest php 
    // this could also be an enum
    const SEVERITIES = [
        "debug" => 0,
        "info" => 1,
        "warning" => 2,
        "error" => 3,
    ];

    protected int $minium_severity_level = 3;

    public function __construct(
        // this could be many different targets
        // https://flysystem.thephpleague.com/docs/
        protected string $minium_severity = "error",
        protected ?Filesystem $filesystem = null,
        protected string $path = "",
    ) {
        if (!in_array($minium_severity, array_flip(self::SEVERITIES))) {
            throw new Exception("Logger severity can only be " . implode(',', self::SEVERITIES));
        }

        $this->minium_severity_level = self::SEVERITIES[$minium_severity];
    }

    /**
     * @throws FilesystemException|UnableToWriteFile
     */
    public function debug(string $message): void
    {
        $this->log("debug", $message);
    }

    /**
     * @throws FilesystemException|UnableToWriteFile
     */
    public function info(string $message): void
    {
        $this->log("info", $message);
    }

    /**
     * @throws FilesystemException|UnableToWriteFile
     */
    public function warning(string $message): void
    {
        $this->log("warning", $message);
    }

    /**
     * @throws FilesystemException|UnableToWriteFile
     */
    public function error(string $message): void
    {
        $this->log("error", $message);
    }

    /**
     * to improve performance we could go for stream writing here
     *
     * @throws FilesystemException|UnableToWriteFile
     */
    protected function log(string $severity, string $message): void
    {
        $severity_level = self::SEVERITIES[$severity];

        if ($severity_level < $this->minium_severity_level) {
            return;
        }

        $line = "[$severity] $message" . PHP_EOL;
        if ($this->filesystem) {
            $this->filesystem->write($this->path, $line);
        } else {
            echo "$line";
        }
    }
}
