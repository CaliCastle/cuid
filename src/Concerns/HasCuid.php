<?php

namespace CaliCastle\Concerns;

use CaliCastle\Cuid;

trait HasCuid
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @return false
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @codeCoverageIgnore
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }

    /**
     * Get the CUID prefix.
     *
     * @codeCoverageIgnore
     * @return string
     */
    public static function getCuidPrefix(): string
    {
        return 'c';
    }

    /**
     * The booting method of the model.
     */
    protected static function bootHasCuid()
    {
        static::creating(function (self $model) {
            $model->{$model->getKeyName()} = Cuid::make(self::getCuidPrefix());
        });
    }
}
