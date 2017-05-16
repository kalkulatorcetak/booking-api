<?php

namespace App\Models;

use Dingo\Api\Http\Request;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Model;

class AbstractModel extends Model
{
    use HasTimestamps;

    public static function findById(int $id): AbstractModel
    {
        $request = app('Dingo\Api\Routing\Router')->getCurrentRequest();
        $cacheKey = static::getCacheKey($id, $request->version());
        if (app('cache')->has($cacheKey)) {
            $model = unserialize(app('cache')->get($cacheKey), ['allowed_classes' => [static::class]]);
        } else {
            $model = static::findOrFail($id);
            app('cache')->put($cacheKey, serialize($model), 10);
        }

        return $model;
    }

    protected static function getCacheKey($id, $version)
    {
        return sprintf("%s.%s.%d", $version, class_basename(static::class), $id);
    }
}
