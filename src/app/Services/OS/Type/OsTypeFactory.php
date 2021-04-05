<?php

namespace App\Services\OS\Type;

class OsTypeFactory
{
    protected static $instance = [];

    /**
     * @param string $type
     * @param int $applicationId
     * @return OsTypeInterface
     */
    public static function create(string $type, int $applicationId): OsTypeInterface
    {
        if (!isset(self::$instance[$type])) {
            $class = sprintf('\\%s\Handlers\\%sHandler', __NAMESPACE__, ucfirst($type));

            self::$instance[$type] = app($class, ['applicationId' => $applicationId]);
        }
        return self::$instance[$type];
    }
}
