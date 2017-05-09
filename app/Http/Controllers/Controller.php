<?php

namespace App\Http\Controllers;

use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
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
        $collection = $this->applyParams($query, $params);
        if ($params->hasPagination()) {
            $paginator = $query->paginate($params->getPagination()->getLimit(), ['*'], 'page', $params->getPagination()->getPage());
            $response = $this->response->paginator($paginator, $transformer, $options);
        } else {
            $response = $this->response->collection($collection, $transformer, $options);
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
}
