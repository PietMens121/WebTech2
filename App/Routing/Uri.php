<?php

namespace App\Routing;

class Uri
{
    private string $uri;
    /**
     * @var UriSegment[]
     */
    private array $segments = [];
    private int $length;

    public function __construct(string $uri)
    {
        $this->uri = $uri;

        $slices = explode("/", $uri);
        foreach ($slices as $slice) {
            $segment = new UriSegment($slice);
            $this->segments[] = $segment;
        }

        $this->length = count($this->segments);
    }

    public function matches(Uri $uri) : bool {
        if ($this->length != $uri->getLength()) return false;   // Check if URIs have same amount of segments

        // Check if URI's match
        $segments = $uri->getSegments();
        for ($i = 0; $i < $this->length; $i++) {
            if ($this->segments[$i]->isParameter()) continue;
            if ($this->segments[$i] != $segments[$i]) return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * @return array
     */
    public function getSegments(): array
    {
        return $this->segments;
    }

    /**
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * @param Uri $routeUri
     * @return string[]
     */
    public function extractParameters(Uri $routeUri) : array {
        $parameters = [];

        $routeUriSegments = $routeUri->getSegments();
        for ($i = 0; $i < $routeUri->getLength(); $i++) {
            if ($routeUriSegments[$i]->isParameter())
                $parameters[] = $this->segments[$i]->getName();
        }
        return $parameters;
    }
}