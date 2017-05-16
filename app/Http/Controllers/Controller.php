<?php

namespace App\Http\Controllers;

use App\Validators\RequestValidatorInterface;
use Dingo\Api\Exception\ValidationHttpException;
use Dingo\Api\Http\Request;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
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
        $cacheKey = static::getCacheKey($class, $request);
        $cacheTag = static::getCacheTag($class, $request);
        $transformer = $this->getTransformerByClass($class);
        $query = $class::query();
        $params = $this->parseQueryParams($request);

        if (app('cache')->tags($cacheTag)->has($cacheKey)) {
            if ($params->hasPagination()) {
                $paginator = unserialize(app('cache')->tags($cacheTag)->get($cacheKey));
                $response = $this->response->paginator($paginator, $transformer, $options);
            } else {
                $collection = unserialize(app('cache')->tags($cacheTag)->get($cacheKey));
                $response = $this->response->collection($collection, $transformer, $options);
            }
        } else {
            $collection = $this->applyParams($query, $params);

            if ($params->hasPagination()) {
                $paginator = $query->paginate($params->getPagination()->getLimit(), ['*'], 'page', $params->getPagination()->getPage());
                app('cache')->tags($cacheTag)->put($cacheKey, serialize($paginator), 10);
                $response = $this->response->paginator($paginator, $transformer, $options);
            } else {
                app('cache')->tags($cacheTag)->put($cacheKey, serialize($collection), 10);
                $response = $this->response->collection($collection, $transformer, $options);
            }
        }

        return $response;
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
        } catch(ValidationException $ex) {
            throw new ValidationHttpException($ex->validator->errors());
        }
    }

    public static function getCacheKey(string $class, Request $request): string
    {
        $version = $request->version();
        $query = $request->getQueryString();

        return sprintf("%s.%s.%s", $version, class_basename($class), md5($query));
    }

    public static function getCacheTag($class, $request): array
    {
        $version = $request->version();

        return [sprintf("%s.%sList", $version, class_basename($class))];
    }
}
