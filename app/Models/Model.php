<?php

namespace App\Models;

use App\Api\V1\Observers\ModelObserver;
use App\Contracts\Cacheable;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class Model extends BaseModel
{
    use HasTimestamps;

    public static function boot() : void
    {
        static::observe(new ModelObserver());

        parent::boot();
    }

    public static function findById(int $id): Model
    {
        $model = null;

        if (static::class instanceof Cacheable) {
            $model = static::loadFromCacheById($id);
        }

        if ($model === null) {
            $model = self::loadFromDatabaseById($id);
            if (static::class instanceof Cacheable) {
                static::saveToCache($id, $model);
            }
        }

        return $model;
    }

    protected static function loadFromCacheById(int $id): ?Model
    {
        $model = null;
        $cacheKey = static::getCacheKey($id);

        if (app('cache')->has($cacheKey)) {
            $model = unserialize(app('cache')->get($cacheKey), ['allowed_classes' => [static::class]]);
        }

        return $model;
    }

    public static function getCacheKey(int $id): string
    {
        return sprintf('%s.%d', static::class, $id);
    }

    protected static function loadFromDatabaseById(int $id): Model
    {
        try {
            $model = static::findOrFail($id);
        } catch (ModelNotFoundException $ex) {
            throw new NotFoundHttpException($ex->getMessage(), null, $ex->getCode());
        }

        return $model;
    }

    protected static function saveToCache($id, $model): void
    {
        $cacheKey = static::getCacheKey($id);

        app('cache')->put($cacheKey, serialize($model), 10);
    }
}
