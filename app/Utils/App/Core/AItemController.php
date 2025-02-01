<?php

namespace App\Utils\App\Core;

use App\Utils\App\Core\Traits\TGeneralErrorHandler;
use App\Utils\App\Core\Traits\TResponseDispatch;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Validator;

abstract class AItemController extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    use TResponseDispatch, TGeneralErrorHandler;

    private $data = [];
    private $queryParams = [];

    protected function validateRequest(Request $request, array $rules, string $method = 'all')
    {
        $validator = Validator::make($this->getRequestParameters($request,$method), $rules);

        if ($validator->fails()) {
            return $this->setError('An error occured', $validator->errors(), 422);
        }

        $this->setData($validator->validated());

        return true;
    }

    protected function getRequestParameters(Request $request, string $method = null): array
    {
        if (!is_null($method))
        {
            return match ($method) {
                Request::METHOD_GET => $request->query(),
                Request::METHOD_POST => $request->post(),
                default => $request->all(),
            };
        }

        return match ($request->method()) {
            Request::METHOD_GET => $request->query(),
            Request::METHOD_POST => $request->post(),
            default => $request->all(),
        };
    }

    protected function filterQueryParams(Request $request, array $rules): array
    {
        $validator = Validator::make($request->query(), $rules);

        $this->setQueryParams($validator->validated());

        return $this->getQueryParams();
    }

    protected function getValue($key)
    {
        $data = $this->getData();

        if ( isset($data[$key]) )
            return $data[$key];

        return null;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): void
    {
        $this->data = $data;
    }

    public function getQueryParamValue(string $key)
    {
        if ( isset($this->queryParams[$key]) )
            return $this->queryParams[$key];

        return null;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function setQueryParams(array $queryParams): void
    {
        $this->queryParams = $queryParams;
    }
}
