<?php

namespace App\Exceptions;
use BadMethodCallException;
use ErrorException;
//use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
//use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\MessageBag;
use Illuminate\Validation\ValidationException;
use InvalidArgumentException;
use LogicException;
use ReflectionException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use App\Traits\ApiResponser;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponser;
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {

        if ($exception instanceof InvalidArgumentException) {
            return $this->errorResponse($exception->getMessage(), 405);
        }

        if ($exception instanceof ModelNotFoundException && $request->wantsJson()) {
//            return response()->json(['message' => 'Table parameter not valid!'], 404);
            return $this->errorResponse($exception->getMessage(), 405);

        }

        if ($exception instanceof ErrorException) {
            return $this->errorResponse($exception->getMessage(), 405);
        }

        if ($exception instanceof ReflectionException) {
            return $this->errorResponse($exception->getMessage(), 405);
        }

        if ($exception instanceof LogicException) {
            return $this->errorResponse($exception->getMessage(), 405);
        }

        if ($exception instanceof BadMethodCallException) {
            return $this->errorResponse($exception->getMessage(), 405);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->errorResponse('Not Authorized', 403);
        }

        if ($exception instanceof QueryException) {
            return $this->errorResponse($exception->getMessage(), 405);
        }


        if ($exception instanceof NotFoundHttpException) {
            return $this->errorResponse('The Url Specified cannnot be found', 404);
        }

        if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        }

        if ($exception instanceof HttpException) {
            return $this->errorResponse('error', $exception->getStatusCode());
        }


        return parent::render($request, $exception);
    }

    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        $errors = $e->validator->errors()->getMessages();

        return $this->errorResponse($errors,    422);

    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {

        return $this->errorResponse('Not Authenticated', 401);
    }

}
