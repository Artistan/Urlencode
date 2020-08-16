<?php

namespace Artistan\Urlencode\Extensions\Routing;

use Artistan\Urlencode\Request;

class RouteParameterBinder extends \Illuminate\Routing\RouteParameterBinder
{
    /**
     * Get the parameter matches for the path portion of the URI.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function bindPathParameters($request)
    {
        $path = '/'.ltrim($request->path(), '/');

        preg_match($this->route->compiled->getRegex(), $path, $matches);

        return $this->matchToKeys(array_map('rawurldecode', array_slice($matches, 1)));
    }


}
