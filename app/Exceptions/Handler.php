<?php

namespace App\Exceptions;

use Exception;
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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    // public function prepareJsonResponse($request, Exception $e)
    // {
    //     $data['status'] = $this->isHttpException($e) ? $e->getStatusCode() : 500;
    //     if(config('app.debug')){
    //         $data['file'] = $e->getFile();
    //         $data['line'] = $e->getLine();
    //         $data['traces'] = $e->getTrace();
    //     }

    //     $headers = $this->isHttpException($e) ? $e->getHeaders() : [];
    //     $message = $e->getMessage();
    //     if ($message == 'The given payload is invalid.') {
    //         $message = '服务错误，请重试';
    //     }
    //     return new JsonResponse(
    //         [ 'code'=>1,
    //             'data'=>$data,
    //             'message'=>$data['status'].':'.$message
    //         ], 200, $headers,
    //         JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
    //     );
        
    // }
}
