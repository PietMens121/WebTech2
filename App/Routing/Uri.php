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

    /**
     * Constructor
     * @param string $uri
     */
    public function __construct(string $uri)
    {
        $this->uri = $uri;

        // Slice the URI into segments
        $slices = explode("/", $uri);
        foreach ($slices as $slice) {
            $this->segments[] = new UriSegment($slice);
        }

        // Set length to amount of segments
        $this->length = count($this->segments);
    }

    /**
     * Checks if this URI matches another URI.
     * @param Uri $uri URI to compare.
     * @return bool True if they match.
     */
    public function matches(Uri $uri): bool
    {
        // Check if URIs have same amount of segments
        if ($this->length != $uri->getLength()) {
            return false;
        }

        // Check if URI's match
        $segments = $uri->getSegments();
        for ($i = 0; $i < $this->length; $i++) {
            if ($this->segments[$i]->isParameter()) {
                continue;
            }   // Skip iteration if segment is a parameter
            if ($this->segments[$i] != $segments[$i]) {
                return false;
            }
        }

        return true;
    }

    /**
     * Getter for the URI string.
     * @return string
     */
    public function getUri(): string
    {
        return $this->uri;
    }

    /**
     * Getter for {@link UriSegment}s of the URI.
     * @return UriSegment[]
     */
    public function getSegments(): array
    {
        return $this->segments;
    }

    /**
     * Getter for amount of segments in URI.
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * Extracts the parameters from a given URI based on the matching Route URI.
     * @param Uri $routeUri The matching Route URI.
     * @return string[] An array containing the extracted parameters.
     */
    public function extractParameters(Uri $routeUri): array
    {
        $parameters = [];
        $routeUriSegments = $routeUri->getSegments();

        for ($i = 0; $i < $routeUri->getLength(); $i++) {
            // If a segment corresponds to a parameter store the segment name.
            if ($routeUriSegments[$i]->isParameter()) {
                $parameters[] = $this->segments[$i]->getName();
            }
        }
        return $parameters;
    }
}