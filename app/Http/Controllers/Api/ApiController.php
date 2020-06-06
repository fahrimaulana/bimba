<?php

namespace App\Http\Controllers\Api;

use Exception, Log;
use League\Fractal\Manager;
use Illuminate\Http\Request;
use League\Fractal\Resource\Item;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use League\Fractal\Resource\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Transformers\Serializer\CustomSerializer;
use App\Exceptions\RestApiValidationErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection as IlluminateCollection;

class ApiController extends Controller
{
    protected $statusCode = 200;
    protected $includedRelation = [];

    protected function getStatusCode()
    {
        return $this->statusCode;
    }

    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    protected function respond($data, $headers = [])
    {
        return response()->json($data, $this->statusCode, $headers);
    }

    protected function makeResponse($data = null, $message = null, $headers = [], $result = 'success')
    {
        $result = [
            'status' => $result,
            'status_code' => $this->statusCode,
        ];
        if(!empty($message)) $result['message'] = $message;
        $result['data'] = $data;

        return $this->respond($result, $headers);
    }

    /**
     * Respond JSON data with paginate or simplePaginate method
     *
     * @author JimmyJS <jimmysetiawan.js@gmail.com>
     * @param \Illuminate\Pagination\Paginator | \Illuminate\Pagination\LengthAwarePaginator $item
     *
     * @return string
     */
    protected function respondWithPaginator($item, $transformedItems = [])
    {
        $data = [
            'status_code' => $this->statusCode,
            'status' => 'success'
        ];
        $data['paginator'] = [
            'current_page' => (int) $item->currentPage(),
            'per_page' => (int) $item->perPage(),
            'previous_page' => ($item->previousPageUrl()) ? (int) $item->currentPage() - 1 : (int) null,
            'next_page' => ($item->nextPageUrl()) ? (int) $item->currentPage() + 1 : (int) null
        ];
        if ($item instanceof LengthAwarePaginator) { // paginate()
            $data['paginator']['total_items'] = (int) $item->total();
            $data['paginator']['total_pages'] = (int) $item->lastPage();
        }
        $data['data'] = (!empty($transformedItems)) ? $transformedItems : $item->items();

        return $this->respond($data);
    }

    /**
     * Respond JSON header-detail data with paginate or simplePaginate method on detail
     *
     * @author JimmyJS <jimmysetiawan.js@gmail.com>
     * @param string $headerName
     * @param \Illuminate\Pagination\Paginator | \Illuminate\Pagination\LengthAwarePaginator $headerData
     * @param string $detailName
     * @param \Illuminate\Pagination\Paginator | \Illuminate\Pagination\LengthAwarePaginator $detailData
     * @param string $result
     *
     * @return string
     */
    protected function respondHeaderDetailWithPaginator($headerName, $headerData, $detailName, $detailData, $result = 'success')
    {
        $data = [ // Buat merge $data dengan paginator
            'status_code' => $this->statusCode,
            'status' => $result,
            'paginator' => [
                'current_page' => $detailData->currentPage(),
                'per_page' => $detailData->perPage(),
                'previous_page' => ($detailData->previousPageUrl()) ? $detailData->currentPage() - 1 : null,
                'next_page' => ($detailData->nextPageUrl()) ? $detailData->currentPage() + 1 : null
            ],
            'data' => [
                $headerName => $headerData,
                $detailName => $detailData->items()
            ]
        ];

        if ($detailData instanceof LengthAwarePaginator) { // paginate()
            $data['paginator']['total_items'] = $detailData->total();
            $data['paginator']['total_pages'] = $detailData->lastPage();
        }

        return $this->respond($data);
    }

    /**
     * Respond JSON of transformed paginate or simplePaginate method
     *
     * @author JimmyJS <jimmysetiawan.js@gmail.com>
     * @param \Illuminate\Pagination\Paginator | \Illuminate\Pagination\LengthAwarePaginator $itemsWithPaginator
     * @param \League\Fractal\TransformerAbstract $transformerClass
     *
     * @return string
     */
    protected function respondTransformWithPaginator($itemsWithPaginator, $transformerClass)
    {
        $transformedItems = $this->transformData($itemsWithPaginator, $transformerClass);

        return $this->respondWithPaginator($itemsWithPaginator, $transformedItems);
    }

    /**
     * Respond JSON of transformed Collection or Models
     *
     * @author JimmyJS <jimmysetiawan.js@gmail.com>
     * @param \Illuminate\Support\Collection | \Illuminate\Database\Eloquent\Model $items
     * @param \League\Fractal\TransformerAbstract $transformerClass
     * @param string $message
     *
     * @return string
     */
    protected function respondTransform($items, $transformerClass, $message = null)
    {
        $transformedItems = $this->transformData($items, $transformerClass);

        return $this->makeResponse($transformedItems, $message);
    }

    /**
     * Convert Eloquent Model / Collection / Paginator / LengthAwarePaginator to array of transformed data
     *
     * @author JimmyJS <jimmysetiawan.js@gmail.com>
     * @param \Illuminate\Database\Eloquent\Model | \Illuminate\Support\Collection | \Illuminate\Pagination\Paginator | \Illuminate\Pagination\LengthAwarePaginator $items
     *
     * @return Array $transformedItems
     */
    protected function transformData($items, $transformerClass)
    {
        $fractal = new Manager;
        $fractal->setSerializer(new CustomSerializer());
        $fractal->parseIncludes($this->includedRelation);
        $this->includedRelation = [];

        if ($items instanceof Paginator || $items instanceof LengthAwarePaginator) {
            $items = $items->items();
            $resource = new Collection($items, $transformerClass);
        } elseif ($items instanceof IlluminateCollection) {
            $resource = new Collection($items, $transformerClass);
        } else {
            $resource = new Item($items, $transformerClass);
        }

        $transformedItems = $fractal->createData($resource)->toArray();

        return $transformedItems;
    }


    /**
     * Add relation to transformers
     *
     * @author JimmyJS <jimmysetiawan.js@gmail.com>
     * @param string | array $includedRelation
     *
     * @return Array $transformedItems
     */
    protected function setIncludedRelation($includedRelation)
    {
        $this->includedRelation = $includedRelation;

        return $this;
    }

    protected function respondNotFound($message = 'Not Found!', $headers = [])
    {
        return $this->setStatusCode(404)->makeResponse(null, $message, $headers, 'error');
    }

    protected function respondUnauthorized($message = 'Unauthorized!', $headers = [])
    {
        return $this->setStatusCode(401)->makeResponse(null, $message, $headers, 'error');
    }

    protected function respondValidationError($message = 'Validation Error!', $headers = [])
    {
        $result = [
            'status' => 'error',
            'status_code' => 422,
            'message' => $message,
            'errors' => [$message]
        ];

        return response()->json($result, 422, $headers);
    }

    protected function respondUnknownError(Exception $e, $message = 'Unknown Error! Process aborted.', $headers = [])
    {
        Log::error("[Unknown Error] {$message}\r\nFile {$e->getFile()}:{$e->getLine()} with message {$e->getMessage()}\r\n{$e->getTraceAsString()}");
        report($e);

        if ($e instanceof ModelNotFoundException) throw $e;

        return $this->respondValidationError($message, $headers);
    }

    protected function respondCreated($message = 'Data has been Created Successfuly!', $data = null, $headers = [])
    {
        return $this->setStatusCode(201)->makeResponse($data, $message, $headers);
    }

    public function validate(Request $request, Array $rules, Array $message = [], Array $customAttributes = [])
    {
        $validation = app('validator')->make($request->all(), $rules, $message);

        if ($validation->fails()) $this->throwRestValidationError($validation->errors()->all(), $validation->errors()->first());

        return true;
    }

    public function throwRestValidationError(Array $errors, $message)
    {
        $error = [
            'status_code' => 422,
            'status' => 'error',
            'message' => $message,
            'errors' => $errors
        ];

        throw new RestApiValidationErrorException($error, $message);
    }
}