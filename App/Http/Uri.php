<?php

namespace App\Http;

use Psr\Http\Message\UriInterface;

class Uri implements  UriInterface
{
    static function parseUri(string $uriString): Uri {
        $uriParts = parse_url($uriString);

        $scheme = $uriParts['scheme'] ?? null;
        $userInfo = $uriParts['user'] ?? '';
        $userInfo .= $uriParts['pass'] ? ':' . $uriParts['pass'] : '';
        $host = $uriParts['host'] ?? '';
        $port = $uriParts['port'] ?? null;
        $path = $uriParts['path'] ?? '';
        $query = $uriParts['query'] ?? '';
        $fragment = $uriParts['fragment'] ?? '';

        return new Uri($scheme, $userInfo, $host, $port, $path, $query, $fragment);
    }

    private $scheme;
    private $userInfo;
    private $host;
    private $port;
    private $path;
    private $query;
    private $fragment;

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

    public function getScheme()
    {
        return $this->scheme;
    }

    public function getAuthority()
    {
        $authority = '';
        if ($this->userInfo !== null || $this->host !== null) {
            $authority .= $this->getUserInfo() . '@';
        }
        $authority .= $this->getHost();
        if ($this->port !== null) {
            $authority .= ':' . $this->getPort();
        }
        return $authority;
    }

    public function getUserInfo()
    {
        return $this->userInfo;
    }

    public function getHost()
    {
        return $this->host;
    }

    public function getPort()
    {
        return $this->port;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getQuery()
    {
        return $this->query;
    }

    public function getFragment()
    {
        return $this->fragment;
    }

    public function withScheme($scheme)
    {
        $clone = clone $this;
        $clone->scheme = $this->filterScheme($scheme);
        return $clone;
    }

    public function withUserInfo($user, $password = null)
    {
        $clone = clone $this;
        $clone->userInfo = $this->filterUserInfo($user, $password);
        return $clone;
    }

    public function withHost($host)
    {
        $clone = clone $this;
        $clone->host = $this->filterHost($host);
        return $clone;
    }

    public function withPort($port)
    {
        $clone = clone $this;
        $clone->port = $this->filterPort($port);
        return $clone;
    }

    public function withPath($path)
    {
        $clone = clone $this;
        $clone->path = $this->filterPath($path);
        return $clone;
    }

    public function withQuery($query)
    {
        $clone = clone $this;
        $clone->query = $this->filterQueryAndFragment($query);
        return $clone;
    }

    public function withFragment($fragment)
    {
        $clone = clone $this;
        $clone->fragment = $this->filterQueryAndFragment($fragment);
        return $clone;
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
        if ($this->path !== null) {
            $uri .= $this->filterPath($this->getPath());
        }
        if ($this->query !== null) {
            $uri .= '?' . $this->filterQueryAndFragment($this->getQuery());
        }
        if ($this->fragment !== null) {
            $uri .= '#' . $this->filterQueryAndFragment($this->getFragment());
        }
        return $uri;
    }

    private function filterScheme($scheme)
    {
        return preg_replace('/[^a-z0-9+.-]+/i', '', $scheme);
    }

    private function filterUserInfo($userInfo)
    {
        return rawurlencode(str_replace(':', '%3A', $userInfo));
    }

    private function filterHost($host)
    {
        return strtolower(preg_replace('/[\x00-\x1f\x7f-\xff]/', '', $host));
    }

    private function filterPort($port)
    {
        return $port !== null ? (int)$port : null;
    }

    private function filterPath($path)
    {
        return preg_replace_callback('/[^a-zA-Z0-9_\-.~!&\'()*+,;=%:@\/]++|%(?![A-Fa-f0-9]{2})/u', function($matches) {
            return rawurlencode($matches[0]);
        }, $path);
    }

    private function filterQueryAndFragment($str)
    {
        return preg_replace_callback('/[^a-zA-Z0-9_\-.~!&\'()*+,;=%:@\/?#\[\]]++|%(?![A-Fa-f0-9]{2})/u', function($matches) {
            return rawurlencode($matches[0]);
        }, $str);
    }
}