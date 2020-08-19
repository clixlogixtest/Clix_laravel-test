<?php

namespace App\Exceptions;

use Throwable;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
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
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        if ($this->isHttpException($exception)) {
            if ($exception->getStatusCode() == 404) {
                if ($request->is('api/*')) {
                    return response()->json(['error' => 'Not Found', 'status'  => intval(Response::HTTP_NOT_FOUND)], 200);
                }

                return response()->view('pages.page-404', [], 404);
            }

            if ($exception->getStatusCode() == 405) {
                if ($request->is('api/*')) {
                    return response()->json(['error' => 'The POST method is not supported for this route.', 'status'  => intval(405)], 200);
                }

                
            }
            if ($exception->getStatusCode() == 500) {
                return response()->view('pages.page-500', [], 404);
            }
        }
     
        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    { //echo $request->expectsJson();
        //if ($request->expectsJson()) {
            return response()->json($resp = [
                'error' => 'Unauthenticated.',
                'status'  => intval(Response::HTTP_NOT_FOUND)
            ], 200);
        /*}

        return redirect()->guest(route('login'));*/
    }
}
