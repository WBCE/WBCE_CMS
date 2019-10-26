<?php

namespace Wbce;

use InvalidArgumentException;
use RuntimeException;

class Loader
{
    /**
     * @var array
     */
    protected $classDirectories = [];

    /**
     * @var array
     */
    protected $fileTemplates = [];

    /**
     * @var array
     */
    protected $classRegistry = [];

    /**
     * Constructor.
     *
     * @param array $funcDirectories Function directories
     * @param array $classDirectories Class directories
     * @param bool $autoload Set TRUE to enable autoload per default
     */
    public function __construct(array $funcDirectories = [], array $classDirectories = [], bool $autoload = false)
    {
        // Load functions from directories
        $this->loadFunctionsFromDirectories($funcDirectories);

        // Add class directories
        $this->addClassDirectories($classDirectories);

        $this->fileTemplates = [
            function ($className) {
                return sprintf('%s.php', $className);
            },
            function ($className) {
                return sprintf('%s.php', strtolower($className));
            },
            function ($className) {
                return sprintf('class.%s.php', strtolower($className));
            },
            function ($className) {
                return sprintf('%s.class.php', strtolower($className));
            },
            function ($className) {
                return sprintf('%s.class.inc', strtolower($className));
            },
            function ($className) {
                return sprintf('%s.inc', strtolower($className));
            },
            function ($className) {
                return sprintf('%s.inc.php', strtolower($className));
            },
        ];

        if ($autoload) {
            $this->registerAutoload();
        }
    }

    /**
     * Add file template.
     *
     * @param callable $template
     *
     * @example addFileTemplate(function ($className) {
     *   return sprintf('%s.inc.php', strtolower($className));
     * });
     */
    public function addFileTemplate(callable $template): void
    {
        $this->fileTemplates[] = $template;
    }

    /**
     * Load functions from directories.
     *
     * @param array $directories Function directories
     *
     * @return static
     */
    public function loadFunctionsFromDirectories(array $directories): self
    {
        foreach ($directories as $directory) {
            $this->loadFunctionsFromDirectory($directory);
        }

        return $this;
    }

    /**
     * Load functions from directory.
     *
     * @param string $directory Function directory
     *
     * @return static
     */
    public function loadFunctionsFromDirectory(string $directory): self
    {
        foreach (glob($directory . '/*.php') as $functionFilePath) {
            require_once $functionFilePath;
        }

        return $this;
    }

    /**
     * Register class file.
     *
     * @param string $className Class name
     * @param string $classFilePath Absolute class file path
     * @param bool $overwrite Set TRUE to enable overwrite of current registered classes
     *
     * @return static
     */
    public function registerClassFile(string $className, string $classFilePath, $overwrite = false): self
    {
        if (!is_file($classFilePath)) {
            throw new InvalidArgumentException('Class file path "' . $classFilePath . '" does not exists.');
        }

        if (isset($this->classRegistry[$className]) && !$overwrite) {
            throw new RuntimeException('Class "' . $className . '" already registered.');
        }

        $this->classRegistry[$className] = $classFilePath;

        return $this;
    }

    /**
     * Load class file.
     *
     * @param string $className Name of class
     *
     * @return bool
     */
    public function loadClassFile(string $className): bool
    {
        if (array_key_exists($className, $this->classRegistry)) {
            require_once $this->classRegistry[$className];

            return true;
        }

        foreach ($this->classDirectories as $classDirectory) {
            foreach ($this->fileTemplates as $fileTemplate) {
                $classFileName = $fileTemplate(basename($className));
                $namespaceDirPath = '.' === dirname($className) ? '' : dirname($className) . DIRECTORY_SEPARATOR;
                $classFilePath = $classDirectory . DIRECTORY_SEPARATOR . $namespaceDirPath . $classFileName;
                $normalizedClassFilePath = str_replace(['\\', '/', '//'], DIRECTORY_SEPARATOR, $classFilePath);
                $normalizedClassFilePath = str_replace(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, DIRECTORY_SEPARATOR, $normalizedClassFilePath);
                if (is_file($normalizedClassFilePath)) {
                    require_once $normalizedClassFilePath;

                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Register autoload.
     */
    public function registerAutoload(): void
    {
        spl_autoload_register([$this, 'loadClassFile']);
    }

    /**
     * Add directory.
     *
     * @param string $directory Class directory
     *
     * @return static
     */
    public function addClassDirectory(string $directory): self
    {
        $this->classDirectories[] = $directory;

        return $this;
    }

    /**
     * Add directories.
     *
     * @param array $directories Class directories
     *
     * @return static
     */
    public function addClassDirectories(array $directories): self
    {
        $this->classDirectories = array_merge($this->classDirectories, $directories);

        return $this;
    }
}
