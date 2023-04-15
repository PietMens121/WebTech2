<?php

namespace App\Http;

use App\Templating\Render;
use JetBrains\PhpStorm\NoReturn;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class Response implements ResponseInterface
{
    /**
     * Creates a redirect response.
     * @param string $url The URL to redirect the client to.
     * @return Response The redirect response.
     */
    public static function redirect(string $url): Response
    {
        $response = new Response(null, 302);
        return $response->withHeader('Location', $url);
    }

    /**
     * Other way to write {@link Render::view()}.
     * @param $filename
     * @param array $data
     * @param int $statusCode
     * @return ResponseInterface
     */
    public static function view($filename, array $data = array(), int $statusCode = 200): ResponseInterface
    {
        return Render::view($filename, $data, $statusCode);
    }

    /**
     * Sends a response and terminates the script.
     * @param ResponseInterface $response The response to send.
     * @return void
     */
    #[NoReturn] public static function send(ResponseInterface $response): void { //TODO: Put in other class than Response
        // Extract parameters
        $protocolVersion = $response->getProtocolVersion();
        $statusCode = $response->getStatusCode();
        $reasonPhrase = $response->getReasonPhrase();
        $headers = $response->getHeaders();
        $body = $response->getBody();

        // Set status header
        $statusLine = sprintf('HTTP/%s %d %s', $protocolVersion, $statusCode, $reasonPhrase);
        header($statusLine);

        // Set the HTTP response code
        http_response_code($statusCode);

        // Set the HTTP headers
        foreach ($headers as $header => $value) {
            header(sprintf('%s: %s', $header, implode(', ', $value)));
        }

        // Send the response body
        echo $body;

        // Terminate the script
        exit;
    }


    public const PHRASES = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-status',
        208 => 'Already Reported',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        416 => 'Requested range not satisfiable',
        417 => 'Expectation Failed',
        418 => 'I\'m a teapot',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        425 => 'Unordered Collection',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        510 => 'Not Extended',
        511 => 'Network Authentication Required',
    ];

    private string $protocolVersion;
    private array $headers = [];
    private StreamInterface $body;
    private int $statusCode;
    private ?string $reasonPhrase = null;

    /**
     * Creates a response.
     * @param StreamInterface|null $body The response body.
     * @param int $statusCode The status code of the response.
     * @param array $headers The headers of the response.
     * @param string $protocolVersion The protocol version of the response.
     * @param string $reasonPhrase The reason phrase of the response.
     */
    public function __construct(
        StreamInterface $body = null,
        int $statusCode = 200,
        array $headers = [],
        string $protocolVersion = '1.1',
        ?string $reasonPhrase = null
    ) {
        $this->body = $body ?? new Stream(tmpfile()); // Create stream with temp file if no stream was given TODO: create stream outside of class
        $this->statusCode = $statusCode;
        $this->headers = $headers;
        $this->protocolVersion = $protocolVersion;
        $this->reasonPhrase = $reasonPhrase;
    }

    public function getProtocolVersion(): string
    {
        return $this->protocolVersion;
    }

    public function withProtocolVersion($version): Response
    {
        $new = clone $this;
        $new->protocolVersion = $version;
        return $new;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function hasHeader($name): bool
    {
        return isset($this->headers[strtolower($name)]);
    }

    public function getHeader($name)
    {
        $name = strtolower($name);
        if (!isset($this->headers[$name])) {
            return [];
        }
        return $this->headers[$name];
    }

    public function getHeaderLine($name): string
    {
        return implode(',', $this->getHeader($name));
    }

    public function withHeader($name, $value): Response
    {
        $new = clone $this;
        $new->headers[strtolower($name)] = [$value];
        return $new;
    }

    public function withAddedHeader($name, $value): Response
    {
        $new = clone $this;
        $new->headers[strtolower($name)][] = $value;
        return $new;
    }

    public function withoutHeader($name): Response
    {
        $new = clone $this;
        unset($new->headers[strtolower($name)]);
        return $new;
    }

    public function getBody(): StreamInterface
    {
        return $this->body;
    }

    public function withBody(StreamInterface $body): Response
    {
        $new = clone $this;
        $new->body = $body;
        return $new;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function withStatus($code, $reasonPhrase = ''): Response
    {
        $new = clone $this;
        $new->statusCode = $code;
        $new->reasonPhrase = $reasonPhrase;
        return $new;
    }

    public function getReasonPhrase(): string
    {
        // Get corresponding reason phrase if its null
        return $this->reasonPhrase ?? self::PHRASES[$this->statusCode];
    }
}