<?php

namespace App\Routing;

class UriSegment
{
    private string $name;
    private bool $parameter;

    /**
     * Constructor
     * @param string $segment
     */
    public function __construct(string $segment)
    {
        // Check if segment starts with '{' and ends with '}'.
        $this->parameter = preg_match('/^{.*}$/', $segment);

        //
        if ($this->parameter) $this->name = substr($segment, 1, -1);
        else $this->name = $segment;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isParameter(): bool
    {
        return $this->parameter;
    }
}