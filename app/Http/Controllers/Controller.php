<?php

namespace App\Http\Controllers;

use App\Validators\RequestValidatorInterface;
use Dingo\Api\Exception\ValidationHttpException;
use Dingo\Api\Http\Response;
use Dingo\Api\Routing\Helpers;
use Illuminate\Http\Request;
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
        try {
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
        } catch (\Exception $ex) {
            $this->response->errorInternal("Error occurred while processing the request");
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
}
