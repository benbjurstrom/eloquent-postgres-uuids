<?php

namespace BenBjurstrom\EloquentPostgresUuids;

use Ramsey\Uuid\Uuid;
use Illuminate\Validation\ValidationException;

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
            if(!$model->{$model->getKeyName()}){
                $model->{$model->getKeyName()} = Uuid::uuid4();
            }
        });
    }

    /**
     * Override the getKeyType method. Necessary for Laravel 5.7.14+
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }

    /**
     * Override the getIncrementing method.
     *
     * @return string
     */
    public function getIncrementing(){
        return false;
    }

    /**
     * Override the getCasts method to cast the UUID object to a string
     *
     * @return array
     */
    public function getCasts()
    {
        $this->casts = array_unique(array_merge($this->casts, [$this->getKeyName() => 'string']));

        return parent::getCasts();
    }

    /**
     * Override the resolveRouteBinding method to validate the parameter if
     * the column type is guid
     *
     * @param \Illuminate\Database\Eloquent\Model
     * @return \Illuminate\Database\Eloquent\Model|null
     * @throws ValidationException
     */
    public function resolveRouteBinding($value)
    {
        $type = \DB::connection()
            ->getDoctrineColumn($this->getTable(), $this->getRouteKeyName())
            ->getType()
            ->getName();

        if($type === 'guid'){
            $validator = app('validator')->make(
                ['id' => $value],
                ['id' => [new ValidateUuid]]
            );

            if (! $validator->passes()) {
                throw new ValidationException($validator);
            }
        }

        return parent::resolveRouteBinding($value);
    }

}
