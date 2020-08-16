<?php

namespace Artistan\Urlencode\Extensions\Routing;

use Illuminate\Routing\Exceptions\UrlGenerationException;
use Illuminate\Routing\RouteUrlGenerator as RUG;

class RouteUrlGenerator extends RUG
{
    /**
     * Characters that should not be URL encoded.
     *
     * @var array
     */
    public $dontEncode = [
    ];

    /**
     * Generate a URL for the given route.
     *
     * @param  \Illuminate\Routing\Route  $route
     * @param  array  $parameters
     * @param  bool  $absolute
     * @return string
     *
     * @throws \Illuminate\Routing\Exceptions\UrlGenerationException
     */
    public function to($route, $parameters = [], $absolute = false)
    {
        $domain = $this->getRouteDomain($route, $parameters);

        // First we will construct the entire URI including the root and query string. Once it
        // has been constructed, we'll make sure we don't have any missing parameters or we
        // will need to throw the exception to let the developers know one was not given.
        $uri = $this->addQueryString($this->url->format(
            $root = $this->replaceRootParameters($route, $domain, $parameters),
            $this->replaceRouteParameters($route->uri(), $parameters),
            $route
        ), $parameters);

        if (preg_match('/\{.*?\}/', $uri)) {
            throw UrlGenerationException::forMissingParameters($route);
        }
dd($uri);
        // Once we have ensured that there are no missing parameters in the URI we will encode
        // the URI and prepare it for returning to the developer. If the URI is supposed to
        // be absolute, we will return it as-is. Otherwise we will remove the URL's root.
        $uri = strtr(rawurlencode($uri), $this->dontEncode);

        if (! $absolute) {
            $uri = preg_replace('#^(//|[^/?])+#', '', $uri);

            if ($base = $this->request->getBaseUrl()) {
                $uri = preg_replace('#^'.$base.'#i', '', $uri);
            }

            return '/'.ltrim($uri, '/');
        }

        return $uri;
    }

    /**
     * encode callback
     * don't decode multi-dimensional arrays, they are query string params
     *
     * @param  string|array  $param
     * @return string
     */
    function encode($param)
    {
        if (! is_array($param)) {
            return rawurlencode($param);
        }

        return $param;
    }

    /**
     * decode callback
     * don't decode multi-dimensional arrays, they are query string params
     *
     * @param  string|array  $param
     * @return string
     */
    function decode(&$param)
    {
        if (! is_array($param) && ! is_object($param)) {
            return rawurldecode($param);
        }

        return $param;
    }
}
