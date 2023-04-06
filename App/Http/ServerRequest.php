<?php

namespace App\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

class ServerRequest implements ServerRequestInterface
{
    public static function createFromGlobals() : ServerRequest {
        $method = $_SERVER['REQUEST_METHOD'];
        $requestTarget = $_SERVER['REQUEST_URI'];
        $protocolVersion = $_SERVER['SERVER_PROTOCOL'];
        $uri = Uri::parseUri($_SERVER['REQUEST_URI']);

        $headers = [];
        foreach ($_SERVER as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $headerName = str_replace('_', '-', ucwords(strtolower(substr($key, 5)), '_'));
                $headers[$headerName] = $value;
            }
        }

        return new ServerRequest(
            $method,
            $uri,
            $headers,
            $protocolVersion,
            $requestTarget,
            $_COOKIE,
            $_GET,
            []
        );
    }

    private string $protocolVersion;
    private array $headers;
    private string $requestTarget;
    private string $method;
    private UriInterface $uri;
    private array $cookieParams;
    private array $queryParams;
    private array $attributes;

    public function __construct(
        string $method,
        UriInterface $uri,
        array $headers = [],
        string $protocolVersion = '1.1',
        string $requestTarget = '',
        array $cookieParams = [],
        array $queryParams = [],
        array $attributes = []
    ) {
        $this->method = $method;
        $this->uri = $uri;
        $this->headers = $headers;
        $this->protocolVersion = $protocolVersion;
        $this->requestTarget = $requestTarget;
        $this->cookieParams = $cookieParams;
        $this->queryParams = $queryParams;
        $this->attributes = $attributes;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return is_null($this->headers[$name]);
    }

    public function getHeader($name)
    {
        return $this->headers[$name];
    }

    public function getRequestTarget(): string
    {
        return $this->requestTarget;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    public function getServerParams(): array
    {
        return $_SERVER;
    }

    public function getCookieParams(): array
    {
        return $this->cookieParams;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getAttribute($name, $default = null)
    {
        $attribute = $this->attributes[$name];
        return is_null($attribute) ? $default : $attribute;
    }


    //--------------------------------------------------------


    public function withProtocolVersion($version) : null
    {
        return null;
    }

    public function getHeaderLine($name) : null
    {
        return null;
    }

    public function withHeader($name, $value) : null
    {
        return null;
    }

    public function withAddedHeader($name, $value) : null
    {
        return null;
    }

    public function withoutHeader($name) : null
    {
        return null;
    }

    public function getBody() : null
    {
        return null;
    }

    public function withBody(StreamInterface $body) : null
    {
        return null;
    }

    public function withRequestTarget($requestTarget) : null
    {
        return null;
    }

    public function withMethod($method) : null
    {
        return null;
    }

    public function withUri(UriInterface $uri, $preserveHost = false) : null
    {
        return null;
    }

    public function withCookieParams(array $cookies) : null
    {
        return null;
    }

    public function withQueryParams(array $query) : null
    {
        return null;
    }

    public function getUploadedFiles() : null
    {
        return null;
    }

    public function withUploadedFiles(array $uploadedFiles) : null
    {
        return null;
    }

    public function getParsedBody() : null
    {
        return null;
    }

    public function withParsedBody($data) : null
    {
        return null;
    }

    public function withAttribute($name, $value) : null
    {
        return null;
    }

    public function withoutAttribute($name) : null
    {
        return null;
    }
}