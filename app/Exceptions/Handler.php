<?php

namespace App\Exceptions;

use HttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (MethodNotAllowedException $exception) {
            return response($exception->getMessage(), Response::HTTP_METHOD_NOT_ALLOWED);
        });
        $this->renderable(function (RouteNotFoundException $exception) {
            return response($exception->getMessage(), Response::HTTP_NOT_FOUND);
        });
        $this->renderable(function (ValidationException $exception) {
            return response($exception->validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        });
        $this->renderable(function (ModelNotFoundException $exception) {
            $modelName = strtolower(class_basename($exception->getModel()));
            $errorMessage = 'Does not exists any ' . $modelName . ' with the specified data';
            return response($errorMessage, Response::HTTP_NOT_FOUND);
        });
        $this->renderable(function (AuthenticationException $exception, $request) {
            if ($request->is('api/*')) {
                return response('Unauthenticated', 401);
            }
            return redirect()->back();
        });
        $this->renderable(function (AuthorizationException $exception) {
            return response($exception->getMessage(), Response::HTTP_UNAUTHORIZED);
        });
        $this->renderable(function (NotFoundHttpException $exception) {
            return response('The url cannot be found', Response::HTTP_NOT_FOUND);
        });
        $this->renderable(function (HttpException $exception) {
            return response($exception->getMessage(), Response::HTTP_EXPECTATION_FAILED);
        });
        $this->renderable(function (\Exception $exception) {
            return response($exception->getMessage(), 500);
        });
        $this->reportable(function (Throwable $e) {
            return response($e->getMessage(), 500);
        });
    }
}
