<?php


namespace App\Traits;
use Illuminate\Http\Resources\Json\Resource;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use stdClass;

trait ApiResponser
{

    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json([

            'status_code' => $code,
            'error' => $message

        ], $code);
    }

    protected function showAll( $collection, $code = 200)
    {

        return $this->successResponse([

            'status_code' => $code,
            'status' => "success",
            'data' =>  $collection

        ], $code);

    }

    protected function showOne($instance, $code = 200)
    {

        return $this->successResponse([

            'status_code' => $code,
            'status' => "success",
            'data' =>  [

                'book' => $instance

            ]

        ], $code);

    }
    protected function showOneUpdate($message, $code, $instance = [])
    {

        return $this->successResponse([

            'status_code' => $code,
            'status' => "success",
            'message' => $message,
            'data' => $instance

        ], $code);

    }
    protected function showOneTwo( $instance, $code = 200)
    {

        return $this->successResponse([

            'status_code' => $code,
            'status' => "success",
            'data' => $instance

        ], $code);

    }

    protected function showOneDelete( $message, $code = 200)
    {

        return $this->successResponse([

            'status_code' => $code,
            'status' => "success",
            'message' => $message,
            'data' => []

        ], 200);

    }

    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message], $code);
    }


}
