<?php namespace Artistan\Urlencode;

use Illuminate\Http\Request as IllRequest;
use Illuminate\Support\Str;

class Request extends IllRequest {

    /**
     * decode path decodes the ENTIRE thing before passing it onto the router.
     *
     * Get the current decoded path info for the request.
     *
     * @return string
     */
    public function decodedPath()
    {
        return rawurldecode($this->path());
    }

    /**
     * Determine if the current request URI matches a pattern.
     *
     * @param  mixed  ...$patterns
     * @return bool
     */
    public function is(...$patterns)
    {
        $path = $this->path();

        foreach ($patterns as $pattern) {
            if (Str::is($pattern, $path)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get all of the segments for the request path.
     *
     * @return array
     */
    public function segments()
    {
        $segments = explode('/', $this->path());

        return array_values(array_filter($segments, function ($value) {
            return $value !== '';
        }));
    }

}
