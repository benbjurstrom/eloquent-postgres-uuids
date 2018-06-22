<?php

namespace BenBjurstrom\EloquentPostgresUuids;

use Ramsey\Uuid\Uuid;

trait HasUuid
{
    /**
     * Set a uuid
     *
     * @return void
     */
    public static function bootHasUuid()
    {
        static::creating(function ($model) {
            $model->id = Uuid::uuid4();
        });
    }

    /**
     * Override the getCasts method to cast the UUID object to a string
     *
     * @return void
     */
    public function getCasts()
    {
        $this->casts = array_unique(array_merge($this->casts, ['id' => 'string']));

        return parent::getCasts();
    }
}