<?php namespace Artistan\Urlencode\Extensions\Routing\Matching;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;

class UriValidator extends \Illuminate\Routing\Matching\UriValidator {

    /**
     * Validate a given rule against a route and request.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public function matches(Route $route, Request $request)
    {
        dd( $route,  $request);
        $path = rtrim($request->path(), '/') ?: '/';
        return preg_match($route->getCompiled()->getRegex(), $path);
    }

}
