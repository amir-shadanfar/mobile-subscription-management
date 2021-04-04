<?php

namespace App\Services\OS\Type;

class OsTypeFactory
{
    protected static $instance = [];

    /**
     * make Factory class Singleton to save memory
     *
     * @param string $type
     * @return OsTypeInterface
     */
    public static function create(string $type): OsTypeInterface
    {
        if (!isset(self::$instance[$type])) {
            $class = sprintf('\\%s\Handlers\\%sHandler', __NAMESPACE__, ucfirst($type));
            self::$instance[$type] = app($class);
        }
        return self::$instance[$type];
    }
}
