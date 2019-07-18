<?php
// All of those framework files has been replaced
// now if a file loads one of this Libs multiple times the
// include_once stops the script from throwing errors
// for classes this file can be even completely empty as
// the autoloader normally takes care


require_once __dir__ . '/classes/Persistence/Database.php';
require_once __dir__ . '/classes/Persistence/Result.php';
require_once __dir__ . '/classes/Persistence/DatabaseException.php';

class database extends Persistence\Database
{
    /**
     * Constructor.
     *
     * @param array $options Database options
     */
    public function __construct(array $options = [])
    {
        parent::__construct(array_merge([
            'throwExceptions' => false,
            'triggerDeprecatedError' => false,
        ], $options));
    }


}
