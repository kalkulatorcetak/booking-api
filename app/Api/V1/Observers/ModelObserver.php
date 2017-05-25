<?php

namespace App\Api\V1\Observers;

use App\Http\Controllers\Controller;
use App\Models\Model;
use Dingo\Api\Routing\Router;

class ModelObserver
{
    public function created(Model $model): void
    {
        $this->clearModelListCache($model);
    }

    public function updated(Model $model): void
    {
        $this->clearModelCache($model);
        $this->clearModelListCache($model);
    }

    public function deleted(Model $model): void
    {
        $this->clearModelCache($model);
        $this->clearModelListCache($model);
    }

    protected function clearModelCache(Model $model): void
    {
        $request = app(Router::class)->getCurrentRequest();
        $modelClass = get_class($model);
        $cacheKey = $modelClass::getCacheKey($model->id, $request);
        $cacheTag = Controller::getCacheTag($modelClass, $request);

        app('cache')->tags($cacheTag)->forget($cacheKey);
    }

    protected function clearModelListCache(Model $model): void
    {
        $request = app(Router::class)->getCurrentRequest();
        $modelClass = get_class($model);
        $cacheTag = Controller::getCacheTag($modelClass, $request);

        app('cache')->tags($cacheTag)->flush();
    }
}
