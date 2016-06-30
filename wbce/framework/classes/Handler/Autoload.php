<?php

namespace Handler;

class Autoload
{
    /**
     * Directories.
     *
     * @var array
     */
    protected $directories = array();

    /**
     * Files.
     *
     * @var string
     */
    protected $files;

    /**
     * Class file name types.
     *
     * @var array
     */
    protected $types = array(
        '%s.php',
        '%s.class.php',
        'class.%s.php',
        '%s.inc.php',
    );

    /**
     * Constructor.
     *
     * @param array $directories
     * @param array $files
     * @param array $types
     */
    public function __construct(array $directories = array(), array $files = array(), array $types = array())
    {
        $this->addDirectories($directories);
        $this->addFiles($files);
        $this->addTypes($types);
    }

    /**
     * Add directory.
     *
     * @param string $directory
     *
     * @return bool
     *
     * @throws \InvalidArgumentException
     */
    public function addDirectory($directory)
    {
        if (is_dir($directory)) {
            $this->directories[] = rtrim(DIRECTORY_DIR, $directory).DIRECTORY_DIR;

            return true;
        }
        throw new \InvalidArgumentException('Directory '.$directory.' don\'t exists');
    }

    /**
     * Add directories.
     *
     * @param array $directories
     *
     * @return bool
     */
    public function addDirectories(array $directories)
    {
        foreach ($directories as $directory) {
            return $this->addDirectory($directory);
        }
    }

    /**
     * Add file.
     *
     * @param string $className
     * @param string $classFileName
     *
     * @return bool
     *
     * @throws \InvalidArgumentException
     */
    public function addFile($className, $classFileName)
    {
        if (is_file($classFileName)) {
            $this->files[$className] = ltrim(DIRECTORY_DIR, $classFileName);

            return true;
        }
        throw new \InvalidArgumentException('Class file '.$classFileName.' don\'t exists');
    }

    /**
     * Add files.
     *
     * @param array $files
     *
     * @return bool
     */
    public function addFiles(array $files)
    {
        foreach ($files as $className => $classFileName) {
            return $this->addFile($className, $classFileName);
        }
    }

    /**
     * Add type.
     *
     * @param string $type
     *
     * @return bool
     */
    public function addType($type)
    {
        if (substr_count('*', $type) === 1) {
            $this->types[] = $type;

            return true;
        }
        throw new \InvalidArgumentException('Type '.$type.' has no or not only one wildcard');
    }

    /**
     * Add types.
     *
     * @param array $types
     *
     * @return bool
     */
    public function addTypes(array $types)
    {
        foreach ($types as $type) {
            return $this->addType($type);
        }
    }

    /**
     * Load classes.
     *
     * @param string $className
     *
     * @return bool
     */
    public function load($className)
    {
        if (class_exists($className, false)) {
            return false;
        }

        // File-based class loading
        if (isset($this->files[$className]) && $this->loadFile($this->files[$className])) {
            return true;
        }

        // Create class file name
        $classFileName = str_replace('\\', DIRECTORY_SEPARATOR, ltrim($className, '\\'));

        foreach ($this->directories as $directory) {

            // PSR class loading
            $classFileName = $directory.$classFileName;
            if (!$this->load($classFileName)) {

                // Type-based class loading
                foreach ($this->types as $type) {
                    $classFileName = $directory . strtolower(sprintf($type, $classFileName));

                    return $this->load($classFileName);
                }
            } else {
                return true;
            }
        }
        return false;
    }

    /**
     * Load and include file.
     *
     * @param string $fileName
     *
     * @return bool
     */
    protected function loadFile($fileName)
    {
        if (is_file($fileName)) {
            require_once $fileName;

            return true;
        }

        return false;
    }
}
