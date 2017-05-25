<?php

namespace App\Http\Controllers;

use App\Validators\RequestValidatorInterface;
use Dingo\Api\Exception\ValidationHttpException;
use Dingo\Api\Http\Request;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Routing\Controller as BaseController;
use League\Fractal\TransformerAbstract;
use LumenApiQueryParser\BuilderParamsApplierTrait;
use LumenApiQueryParser\ResourceQueryParserTrait;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Controller extends BaseController
{
    use Helpers;
    use ResourceQueryParserTrait;
    use BuilderParamsApplierTrait;

    protected function responseByParams(string $class, Request $request, array $options): Response
    {
        $transformer = $this->getTransformerByClass($class);
        $query = $class::query();
        $params = $this->parseQueryParams($request);

        if ($this->isCached($class, $request)) {
            $paginator = $this->loadFromCache($class, $request);
        } else {
            $paginator = $this->applyParams($query, $params);
            $this->saveToCache($class, $request, $paginator);
        }

        return $this->response->paginator($paginator, $transformer, $options);
    }

    protected function isCached(string $class, Request $request): bool
    {
        $cacheKey = static::getCacheKey($class, $request);
        $cacheTag = static::getCacheTag($class, $request);

        return app('cache')->tags($cacheTag)->has($cacheKey);
    }

    protected function loadFromCache(string $class, Request $request)
    {
        $cacheKey = static::getCacheKey($class, $request);
        $cacheTag = static::getCacheTag($class, $request);

        return unserialize(app('cache')->tags($cacheTag)->get($cacheKey));
    }

    protected function getTransformerByClass(string $class): TransformerAbstract
    {
        $transformerClass = sprintf("%sTransformer", str_replace('Models', 'Transformers', $class));

        if (!class_exists($transformerClass)) {
            throw new NotFoundHttpException("Transformer ({$transformerClass}) not found!");
        }

        return new $transformerClass;
    }

    protected function validateRequest(Request $request, RequestValidatorInterface $validator): void
    {
        try {
            $this->validate($request, $validator->getRules(), $validator->getMessages());
        } catch (ValidationException $ex) {
            throw new ValidationHttpException($ex->validator->errors());
        }
    }

    public static function getCacheKey(string $class, Request $request): string
    {
        $version = $request->version();
        $query = $request->getQueryString();

        return sprintf("%s.%s.%s", $version, class_basename($class), md5($query));
    }

    public static function getCacheTag(string $class, Request $request): array
    {
        $version = $request->version();

        return [sprintf("%s.%sList", $version, class_basename($class))];
    }

    protected function saveToCache(string $class, Request $request, LengthAwarePaginator $paginator): void
    {
        $cacheKey = static::getCacheKey($class, $request);
        $cacheTag = static::getCacheTag($class, $request);

        app('cache')->tags($cacheTag)->put($cacheKey, serialize($paginator), 10);
    }
}
