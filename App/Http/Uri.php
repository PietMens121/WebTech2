<?php

namespace App\Http;

use Psr\Http\Message\UriInterface;

class Uri implements  UriInterface
{
    static function parseUri(string $uriString): Uri {
        $uriParts = parse_url($uriString);

        $scheme = $uriParts['scheme'] ?? null;
        $userInfo = $uriParts['user'] ?? '';
        $host = $uriParts['host'] ?? '';
        $port = $uriParts['port'] ?? null;
        $path = $uriParts['path'] ?? '';
        $query = $uriParts['query'] ?? '';
        $fragment = $uriParts['fragment'] ?? '';

        return new Uri($scheme, $userInfo, $host, $port, $path, $query, $fragment);
    }

    private ?string $scheme;
    private string $userInfo;
    private string $host;
    private ?int $port;
    private string $path;
    private string $query;
    private string $fragment;

    public function __construct(string $scheme = null, string $userInfo = '', string $host = '', int $port = null, string $path = '', string $query = '', string $fragment = '')
    {
        $this->scheme = $scheme;
        $this->userInfo = $userInfo;
        $this->host = $host;
        $this->port = $port;
        $this->path = $path;
        $this->query = $query;
        $this->fragment = $fragment;
    }

    public function getScheme(): ?string
    {
        return $this->scheme;
    }

    public function getAuthority(): string
    {
        $authority = $this->getUserInfo() . '@';
        $authority .= $this->getHost();
        if ($this->port !== null) {
            $authority .= ':' . $this->getPort();
        }
        return $authority;
    }

    public function getUserInfo(): string
    {
        return $this->userInfo;
    }

    public function getHost(): string
    {
        return $this->host;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function getFragment(): string
    {
        return $this->fragment;
    }

    public function __toString()
    {
        $uri = '';

        if ($this->scheme !== null) {
            $uri .= $this->getScheme() . ':';
        }
        $authority = $this->getAuthority();
        if ($authority !== '') {
            $uri .= '//' . $authority;
        }

        $uri .= $this->filterPath($this->getPath());
        $uri .= '?' . $this->filterQueryAndFragment($this->getQuery());
        $uri .= '#' . $this->filterQueryAndFragment($this->getFragment());

        return $uri;
    }


    private function filterPath(string $path): ?string
    {
        $reservedChars = 'a-zA-Z0-9_\-.~';
        $subDelims = '!$&\'()*+,;=';
        $encodedChars = '%[A-Fa-f0-9]{2}';
        $regex = '/[^' . $reservedChars . $subDelims . $encodedChars . ']+/u';

        return preg_replace_callback($regex, function ($matches) {
            return rawurlencode($matches[0]);
        }, $path);
    }

    private function filterQueryAndFragment(string $str): ?string
    {
        $reservedChars = 'a-zA-Z0-9_\-.~';
        $genDelims = ':\/?#\[\]@';
        $subDelims = '!$&\'()*+,;=';
        $encodedChars = '%[A-Fa-f0-9]{2}';
        $regex = '/[^' . $reservedChars . $genDelims . $subDelims . $encodedChars . ']+/u';

        return preg_replace_callback($regex, function ($matches) {
            return rawurlencode($matches[0]);
        }, $str);
    }


    //-----------------------------------------------------------------


    public function withScheme($scheme) : null
    {
        return null;
    }

    public function withUserInfo($user, $password = null) : null
    {
        return null;
    }

    public function withHost($host) : null
    {
        return null;
    }

    public function withPort($port) : null
    {
        return null;
    }

    public function withPath($path) : null
    {
        return null;
    }

    public function withQuery($query) : null
    {
        return null;
    }

    public function withFragment($fragment) : null
    {
        return null;
    }
}