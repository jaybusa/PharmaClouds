<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;


use Mail;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use App\Mail\ExceptionOccured;


use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

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
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
        if ($this->shouldReport($exception)) {
            $env_sev = env('APP_ENV');
            $request = request();
            if ($env_sev != 'local') {
            $this->sendEmail($request,$exception); // sends an email
            }
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    // public function render($request, Exception $exception)
    // {
    //     return parent::render($request, $exception);
    // }

    public function sendEmail($request,Exception $exception) {
        try {
            $e = FlattenException::create($exception);
            $handler = new SymfonyExceptionHandler();

            $html = $handler->getHtml($e);
            $env_sev = env('APP_ENV');

            $url = env('SITE_URL')."=".$request->fullUrl();
           
            $email = ['jitu.vank@gmail.com'];
            //$eamil = ['anil.m@3rddigital.com'];
            Mail::to($email)->send(new ExceptionOccured($html, $url));
            echo json_encode([
                'data' => array(),
                'success' => false,
                'status_code' => 400,
                'message' => 'Something went wrong, try again later'
            ]);
            exit;
        } catch (Exception $ex) {
            //dd($ex);
        }
    }

    public function render($request, Exception $exception)
    {
        if ($exception instanceof UnauthorizedHttpException) {
            // detect previous instance
            if ($exception->getPrevious() instanceof TokenExpiredException) {
                //return response()->json(['error' => 'TOKEN_EXPIRED'], $exception->getStatusCode());
                $message = __('messages.common.token_expired');
                return InvalidResponse($message,402);  
            } else if ($exception->getPrevious() instanceof TokenInvalidException) {
                //return response()->json(['error' => 'TOKEN_INVALID'], $exception->getStatusCode());
                $message = __('messages.common.invalid_token');
                return InvalidResponse($message,403);  
            } else if ($exception->getPrevious() instanceof TokenBlacklistedException) {
                //return response()->json(['error' => 'TOKEN_BLACKLISTED'], $exception->getStatusCode());

                $message = __('messages.common.token_blocked');
                return InvalidResponse($message,404);  
            } else {
                //return response()->json(['error' => "UNAUTHORIZED_REQUEST"], 401);
                $message = __('messages.common.token_required');
                return InvalidResponse($message,401);  
            }
        }
        return parent::render($request, $exception);
    }
}
