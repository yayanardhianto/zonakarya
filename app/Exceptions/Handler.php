<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;
use Throwable;

class Handler extends ExceptionHandler
{
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
            // Log all exceptions, especially for job application routes
            if (request()->is('jobs/*/apply') || request()->is('applications/*')) {
                \Log::error('Job Application: Unhandled exception', [
                    'error' => $e->getMessage(),
                    'error_class' => get_class($e),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString(),
                    'url' => request()->fullUrl(),
                    'method' => request()->method(),
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                    'request_data' => request()->except(['cv', 'photo', '_token', 'password']),
                ]);
            }
        });
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => __('Please Login first')], 401);
        }
        $guard = Arr::get($exception->guards(), '0');
        switch ($guard) {
            case 'admin':
                $login = '/admin/login';
                break;

            default:
                $login = '/login';
        }

        return Redirect()->guest($login);
    }

    public function render($request, Throwable $exception)
    {
        // Log errors for job application routes before rendering
        if (($request->is('jobs/*/apply') || $request->is('applications/*')) && !($exception instanceof \Illuminate\Validation\ValidationException)) {
            \Log::error('Job Application: Exception in render', [
                'error' => $exception->getMessage(),
                'error_class' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);
        }
        
        if ($exception instanceof AccessPermissionDeniedException || $exception instanceof DemoModeEnabledException) {
            return $exception->render($request);
        }

        return parent::render($request, $exception);
    }
}
