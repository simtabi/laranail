<?php

namespace Simtabi\Laranail\Http\Controllers\Web;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Route;

abstract class BaseController extends Controller
{

    use
        AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;


    /**
     * Handle response with error
     *
     * @param string $message
     * @param int $status
     * @return RedirectResponse|JsonResponse
     */
    final protected function respondWithError(string $message = '', int $status = 422): RedirectResponse
    {
        $message = $message ??  ('Something go wrong, an error occurred.'); //

        // Handle when ajax request
        if (request()->wantsJson() || request()->ajax())
        {
            return response()->json([
                'result'  => 'error',
                'message' => $message
            ], $status);
        }

        // In case of error always return back to previous page
        return back()->with('error', $message);
    }

    /**
     * Handle response with success
     *
     * @param string $message
     * @param string|null $redirect
     * @param int $status
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    final protected function respondWithSuccess(string $message = '', string $redirect = null, int $status = 200)
    {
        $message = $message?? ('Your request was successfully processed.');
        $request = app('request');

        // First priority check which button user clicked
        if($request->get('_action'))
        {
            // If _action button clicked is "back" then set redirect to null (redirect back)
            // Otherwise use value of _action as redirect to
            $redirect = $request->_action === 'back' ? null : $request->_action;
        }

        // Check if redirect is a route name, do nothing when is full url
        // This allow to pass $redirect as route name or URL
        if(! empty($redirect) && Route::has($redirect)) {
            $redirect = route($redirect);
        }

        //  Handle when ajax request
        if ($request->ajax() || $request->wantsJson()) {

            // When there is redirect, set message in session
            // so that it can be show in redirected page
            if(! empty($redirect)) {
                session()->flash('success', $message);
            }

            return response()->json([
                'result'   => 'success',
                'message'  => $message,
                'redirect' => $redirect
            ], $status);
        }

        // Redirect to route given by controller this can be requested by controller
        if(! empty($redirect)) {
            return redirect($redirect)->with('success', $message);
        }

        // As last fallback just return back
        return back()->with('success', $message);
    }

    /**
     * Handle both request error or success, ajax or not.
     *
     * Usage:
     *
     * $this->handleResponse([
     *      'result' => 'success|error',
     *      'message' => 'Optional message', // There is a default message in case of error or success
     *      'redirect' => 'optional.redirect.route' // Different check
     * ], $status);
     *
     * @param array $response
     * @param null $status
     * @return Application|JsonResponse|RedirectResponse|Redirector
     */
    protected function handleResponse(array $response = [], $status = null)
    {
        // 0. Prepare $response + $status code
        $response = array_merge([
            'result' => 'success',
            'message' => (!empty($response['result']) && $response['result'] === 'error')?
                __('Something go wrong, an error occurred.') : __('Your request was successfully processed.'),
            'redirect' => null,
        ], $response);

        if(! $status) {
            $status = $response['result'] === 'success'? '200' : '422';
        }

        $request = app('request');

        // When an error occurred, always return back to form page
        if($response['result'] === 'error') {
            $response['redirect'] = null;
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json($response, $status);
            }
            return back()->with($response['result'], $response['message']);
        }

        // First priority check which button user clicked
        if($request->has('_action')) {
            // If _action button clicked is "back" then set redirect to null (redirect back)
            // Otherwise use value of _action as redirect to
            $response['redirect'] = $request->_action === 'back' ? null : $request->_action;
        }

        // Check if redirect is a route name, do nothing when is full url
        if(! empty($response['redirect']) && Route::has($response['redirect'])) {
            $response['redirect'] = route($response['redirect']);
        }

        // Handle when ajax request
        if ($request->ajax() || $request->wantsJson()) {

            // When there is redirect, set message in session
            // so that it can be show in redirected page
            if(! empty($response['redirect'])) {
                session()->flash('success', $response['message']);
            }

            return response()->json($response, $status);
        }

        // Redirect to route given by controller this can be requested by controller
        if(! empty($response['redirect'])) {
            return redirect($response['redirect'])->with('success', $response['message']);
        }

        // As last fallback just return back
        return back()->with('success', $response['message']);
    }

    /**
     * Handle database query error
     *
     * @param string|null $code
     * @param string|null $message
     * @return RedirectResponse
     */
    protected function handleDatabaseError(string $code = null, string $message = null): RedirectResponse
    {
        $message            = $message??  ('An error occurred on database.');
        if($code) $message .= " Error code: $code";

        return $this->respondWithError($message);
    }
}
