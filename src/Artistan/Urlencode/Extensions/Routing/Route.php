<?php
/**
 * User: cpeterson
 * Date: 2013
 */

namespace Artistan\Urlencode\Extensions\Routing;

use Artistan\Urlencode\Extensions\Routing\Matching\UriValidator;
use Artistan\Urlencode\Extensions\Routing\RouteParameterBinder;
use Illuminate\Http\Request;
use Illuminate\Routing\Matching\HostValidator;
use Illuminate\Routing\Matching\MethodValidator;
use Illuminate\Routing\Matching\SchemeValidator;

class Route extends \Illuminate\Routing\Route
{
    /**
     * overriding RouteParameterBinder
     *
     * Bind the route to a given request for execution.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return $this
     */
    public function bind(Request $request)
    {
        $this->compileRoute();

        $this->parameters = (new RouteParameterBinder($this))
            ->parameters($request);

        $this->originalParameters = $this->parameters;

        return $this;
    }

    /**
     * overriding UriValidator
     *
     * Get the route validators for the instance.
     *
     * @return array
     */
    public static function getValidators()
    {
        if (isset(static::$validators)) {
            return static::$validators;
        }

        // To match the route, we will use a chain of responsibility pattern with the
        // validator implementations. We will spin through each one making sure it
        // passes and then we will know if the route as a whole matches request.
        return static::$validators = [
            new MethodValidator, new SchemeValidator, new HostValidator, new UriValidator,
        ];
    }
}
