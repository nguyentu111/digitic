<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use App\Exceptions\CustomException\BaseException;
use Throwable;
use Exception;
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }
    // public function render($request, Throwable $e)
    // {
    //    try{
    //     $statusCode = Response::HTTP_BAD_REQUEST;
    //     if ($e->getCode() <= Response::HTTP_INTERNAL_SERVER_ERROR && $e->getCode() > 100) {
    //         $statusCode = $e->getCode();
    //     }
    //     $title = __('server_error');
    //     $message = null;
    //     $errors = null;
    //     switch ($e) {
    //         case $e instanceof AuthenticationException:
    //             $title = __('unauthorized');
    //             $statusCode = Response::HTTP_UNAUTHORIZED;
    //             $message = $e->getMessage();
    //             break;
    //         case $e instanceof NotFoundHttpException:
    //             $title = __('not_found_http_exception');
    //             $message = 'No route found for "'.$request->method().' '.$request->fullUrl().'.';
    //             $statusCode = Response::HTTP_NOT_FOUND;
    //             break;

    //         case $e instanceof ModelNotFoundException:
    //             $title = __('model_not_found');
    //             $message = $e->getMessage() ?? 'Model not found.';
    //             $statusCode = Response::HTTP_NOT_FOUND;
    //             break;
    //         case $e instanceof ValidationException:
    //             $title = __('validation_failed');
    //             $message = $e->getMessage();
    //             $errors = $e->errors();
    //             $statusCode =Response::HTTP_UNPROCESSABLE_ENTITY;
    //             break;
    //         case $e instanceof MethodNotAllowedHttpException:
    //             $title    = "method_not_allowed";
    //             $message  = 'The ' .$request->method().' method is not supported for route '.$request->fullUrl() .'.';
    //             break;
    //         case $e instanceof BaseException:
    //             $title    = $e->getTitle();
    //             $message  =  $e->getMessage();
    //             $statusCode = $e->getStatusCode();
    //             break;
    //         // case $e instanceof ApiException:
    //         //     $title    = $e->getMessage();
    //         //     $statusCode = $e->getCode();
    //         //     $errors = $e->getData();
    //         //     break;
    //         default:
    //             $message = $e->getMessage();
    //             break;
    //     }
    //     if ($request->is('*api*')) {
    //         return $this->makeErrorResponse($statusCode, $title, $errors,$message);
    //     }
        
    //     return response($title, Response::HTTP_BAD_REQUEST);
    //    }catch(Exception $ex){
    //         echo $ex->getMessage();
    //         return $this->makeErrorResponse(500,'server_error', null,$ex->getMessage());
    //    }
    // }
     /**
     * @param int $code
     * @param string $message
     * @param array|null $errors
     * @param mixed|null $data
     * @return Response
     */
    protected function makeErrorResponse(int $code, string $title, ?array $errors = null, $message = null)
    {
        $response = [
            'result' => 'error',
            'title' => $title,
            'error' => $errors,
            'message'=> $message
        ];
        // if (!empty($message)) {
        //     $response['message'] = $message;
        // }
        return response()->json($response, $code);
    }
}