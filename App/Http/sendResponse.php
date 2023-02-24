<?php

namespace App\Http;

use JetBrains\PhpStorm\NoReturn;
use Psr\Http\Message\ResponseInterface;

class sendResponse
{
    #[NoReturn] public static function execute(ResponseInterface $response): void
    {
        if (headers_send()) {
            exit;
        }
        $statusLine = sprintf(
            'HTTP/%s %s %s',
            $response->getProtocolVersion(),
            $response->getStatusCode(),
            $response->getReasonPhrase()
        );

        header($statusLine, true);

        foreach ($response->getHeader() as $name => $values) {
            $responseHeader = sprintf(
                '%s: %s',
                $name,
                $response->getHeaderLine($name)
            );

            header($responseHeader, false);
        }
        echo $response->getBody();
        exit;
    }
}