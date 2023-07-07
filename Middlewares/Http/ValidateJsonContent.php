<?php

namespace Apiato\Core\Middlewares\Http;

use Apiato\Core\Abstracts\Middlewares\Middleware;
use Apiato\Core\Exceptions\MissingJSONHeaderException;
use Closure;
use Illuminate\Http\Request;

class ValidateJsonContent extends Middleware
{
    /**
     * @throws MissingJSONHeaderException
     */
    public function handle(Request $request, Closure $next)
    {
        $acceptHeader = $request->header('accept');
        $contentType = 'application/json';

        // check if the accept header is set to application/json
        // if forcing users to have the accept header is enabled, then throw an exception
        if (!str_contains($acceptHeader, $contentType) && config('apiato.requests.force-accept-header')) {
            throw new MissingJSONHeaderException();
        }

        // the request has to be processed, so get the response after the request is done
        $response = $next($request);

        // return Content-Type application/json in the header if it has not been set to a specific header in the application
        if ($response->headers->get('Content-Type') === 'text/html; charset=UTF-8') {
            $response->headers->set('Content-Type', $contentType);
        }

        // if request doesn't contain in header accept = application/json. Return a warning in the response
        if (!str_contains($acceptHeader, $contentType)) {
            $warnCode = '199'; // https://www.iana.org/assignments/http-warn-codes/http-warn-codes.xhtml
            $warnMessage = 'Missing request header [ accept = ' . $contentType . ' ] when calling a JSON API.';
            $response->headers->set('Warning', $warnCode . ' ' . $warnMessage);

        }

        // return the response
        return $response;
    }
}
