<?php

namespace App\Models;

use App\Api\V1\Observers\ModelObserver;
use Dingo\Api\Http\Request;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;
use Dingo\Api\Routing\Router;

class AbstractModel extends Model
{
    use HasTimestamps;

    public static function boot() : void
    {
        static::observe(new ModelObserver());

        parent::boot();
    }

    public static function findById(int $id): AbstractModel
    {
        $request = app(Router::class)->getCurrentRequest();
        $cacheKey = static::getCacheKey($id, $request);
        if (app('cache')->has($cacheKey)) {
            $model = unserialize(app('cache')->get($cacheKey), ['allowed_classes' => [static::class]]);
        } else {
            $model = static::findOrFail($id);
            app('cache')->put($cacheKey, serialize($model), 10);
        }

        return $model;
    }

    protected static function getCacheKey(int $id, Request $request): string
    {
        $version = $request->version();

        return sprintf('%s.%s.%d', $version, class_basename(static::class), $id);
    }
}
