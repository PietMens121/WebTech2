<?php

namespace App\Http;

class Path
{
    private string $path;
    /**
     * @var PathSegment[]
     */
    private array $segments = [];
    private int $length;

    /**
     * Constructor
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;

        // Slice the Path into segments
        $slices = explode("/", $path);
        foreach ($slices as $slice) {
            $this->segments[] = new PathSegment($slice);
        }

        // Set length to amount of segments
        $this->length = count($this->segments);
    }

    /**
     * Checks if this Path matches another Path.
     * @param Path $path Path to compare.
     * @return bool True if they match.
     */
    public function matches(Path $path): bool
    {
        // Check if Paths have same amount of segments
        if ($this->length != $path->getLength()) {
            return false;
        }

        // Check if Paths match
        $segments = $path->getSegments();
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
     * Getter for the Path string.
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Getter for {@link PathSegment}s of the Path.
     * @return PathSegment[]
     */
    public function getSegments(): array
    {
        return $this->segments;
    }

    /**
     * Getter for amount of segments in Path.
     * @return int
     */
    public function getLength(): int
    {
        return $this->length;
    }

    /**
     * Extracts the parameters from a given Path based on the matching Route Path.
     * @param Path $path The matching Route Path.
     * @return string[] An array containing the extracted parameters.
     */
    public function extractParameters(Path $path): array
    {
        $parameters = [];
        $pathSegments = $path->getSegments();

        for ($i = 0; $i < $path->getLength(); $i++) {
            // If a segment corresponds to a parameter store the segment name.
            if ($pathSegments[$i]->isParameter()) {
                $parameters[] = $this->segments[$i]->getName();
            }
        }
        return $parameters;
    }
}