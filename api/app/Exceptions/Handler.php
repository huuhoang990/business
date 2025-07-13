<?php

namespace App\Exceptions;

use App\Traits\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use ApiResponse;
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param Request $request
     * @param Throwable $e
     * @return JsonResponse|\Illuminate\Http\Response|Response
     * @throws Throwable
     */
    public function render($request, Throwable $e)
    {
        if ($e instanceof QueryException) {
            Log::channel('api')->error("Update Basic Information at". now() .  " : " . $e->getMessage());
            return $this->errorResponse('Fail api', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return $this->errorResponse('Method Not Allowed',Response::HTTP_METHOD_NOT_ALLOWED);
        }

        if ($e instanceof ThrottleRequestsException) {
            return $this->errorResponse('To many request',Response::HTTP_TOO_MANY_REQUESTS);
        }

        if ($e instanceof NotFoundHttpException) {
            return $this->errorResponse('API Not Found', Response::HTTP_NOT_FOUND);
        }

        return parent::render($request, $e);
    }

    /**
     * Response when unauthenticate system
     *
     * @param Request $request
     * @throws Throwable
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $this->errorResponse('Unauthenticated', Response::HTTP_UNAUTHORIZED);
    }
}
