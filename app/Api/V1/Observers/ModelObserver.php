<?php

namespace App\Api\V1\Observers;

use App\Http\Controllers\Controller;
use App\Models\AbstractModel;
use Dingo\Api\Routing\Router;

class ModelObserver
{
    public function created(AbstractModel $model): void
    {
        $this->clearModelListCache($model);
    }

    public function updated(AbstractModel $model): void
    {
        $this->clearModelCache($model);
        $this->clearModelListCache($model);
    }

    public function deleted(AbstractModel $model): void
    {
        $this->clearModelCache($model);
        $this->clearModelListCache($model);
    }

    protected function clearModelCache(AbstractModel $model): void
    {
        $request = app(Router::class)->getCurrentRequest();
        $modelClass = get_class($model);
        $cacheKey = $modelClass::getCacheKey($model->id, $request);
        $cacheTag = Controller::getCacheTag($modelClass, $request);

        app('cache')->tags($cacheTag)->forget($cacheKey);
    }

    protected function clearModelListCache(AbstractModel $model): void
    {
        $request = app(Router::class)->getCurrentRequest();
        $modelClass = get_class($model);
        $cacheTag = Controller::getCacheTag($modelClass, $request);

        app('cache')->tags($cacheTag)->flush();
    }
}