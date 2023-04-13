<?php

namespace App\Templating;

use App\Http\Response;
use Psr\Http\Message\ResponseInterface;

class Render
{
    private const VIEW_FOLDER_PATH = BASE_PATH . '/resources/views';
    private const CACHE_PATH = BASE_PATH . "/temp";
    private static array $sections = [];

    /**
     * The function to initialize the view
     *
     * @param $filename
     * @param array $data
     * @return ResponseInterface
     */
    public static function view($filename, array $data = array(), int $statusCode = 200): ResponseInterface
    {
        // Process the view
        $file = self::prepare($filename);
        $code = self::process($file, $data);

        // Delete temp file
        unlink($file);

        // Create empty response
        $response = new Response(null, $statusCode);

        // Put the HTML in the response
        $response->withHeader('Content-type', 'text/html');
        $response->getBody()->write($code);

        // Return the response
        return $response;
    }

    /**
     * Processes a php file and puts the result in a string.
     * @param string $file The php file to process.
     * @param array $data Data for the templates.
     * @return string The processed html.
     */
    private static function process(string $file, array $data): string
    {
        ob_start();
        extract($data);
        require $file;
        return ob_get_clean();
    }

    /**
     * @param $filename
     * @return false|string
     */
    private static function prepare($filename): bool|string
    {
        $temp = tempnam(self::CACHE_PATH, 'TMP');
        $code = self::includeFiles($filename);
        $code = self::compileView($code);
        file_put_contents($temp, '<?php class_exists(\'' . __CLASS__ . '\') or exit; ?>' . PHP_EOL . $code);
        return $temp;
    }

    /**
     * included files are properly included here
     *
     * @return string|bool
     */
    private static function includeFiles($filename): string|bool
    {
        $code = file_get_contents(self::VIEW_FOLDER_PATH . "/" . $filename);
        preg_match_all('~@(extends|@include)\(([^)]*)\)~is', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            $code = str_replace($value[0], self::includeFiles($value[2]), $code);
        }
        $code = preg_replace('~@(extends|@include)#(#([^)]*)#)#~is', '', $code);
        return $code;
    }

    /**
     * changes all templating function names to correct code
     *
     * @param $code
     * @return mixed
     */
    private static function compileView($code): mixed
    {
        $code = self::compileAsset($code);
        $code = self::compileSection($code);
        $code = self::compileYield($code);
        $code = self::compileEcho($code);
        $code = self::compileIf($code);
        $code = self::compileEndIf($code);
        $code = self::compileElseIf($code);
        $code = self::compileElse($code);
        $code = self::compileForEach($code);
        $code = self::compileEndForEach($code);
        return self::compilePHP($code);
    }

    private static function compileEcho($code): array|string|null
    {
        return preg_replace('~{{\s*(.+?)\s*}}~is', '<?php echo $1 ?>', $code);
    }

    private static function compileIf($code): array|string|null
    {
        return preg_replace('~@if([^;]*).~is', '<?php if$1: ?>', $code);
    }

    private static function compileElseIf($code): array|string|null
    {
        return preg_replace('~@elseif([^;]*).~is', '<?php elseif$1: ?>', $code);
    }

    private static function compileElse($code): array|string|null
    {
        return preg_replace('~@else;~is', '<?php else: ?>', $code);
    }

    private static function compileEndIf($code): array|string|null
    {
        return preg_replace('~@endif[^;]*.~is', '<?php endif; ?>', $code);
    }

    private static function compileForEach($code): array|string|null
    {
        return preg_replace('~@foreach\(([^)]*)\)~', '<?php foreach($1): ?>', $code);
    }

    private static function compileEndForEach($code): array|string|null
    {
        return preg_replace('~@endforeach~', '<?php endforeach ?>', $code);
    }

    private static function compilePHP($code): array|string|null
    {
        return preg_replace('~@php\((.*)(?=\);)..~ism', '<?php $1 ?>', $code);
    }


    private static function compileSection($code): array|string|null
    {
        preg_match_all('~@section\(([^)]*)\)(.*?)@endsection\(\)~is', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            if (!array_key_exists($value[1], self::$sections)) {
                self::$sections[$value[1]] = '';
            }
            if (strpos($value[2], '@parent')) {
                self::$sections[$value[1]] = $value[2];
            } else {
                self::$sections[$value[1]] = str_replace('@parent', self::$sections[$value[1]], $value[2]);
            }
            $code = str_replace($value[0], '', $code);
        }
        return $code;
    }

    private static function compileYield($code): array|string|null
    {
        foreach (self::$sections as $section => $value) {
            $code = preg_replace('~@yield\(' . $section . '\)~is', $value, $code);
        }
        $code = preg_replace('~@yield\(([^)]*)\)~is', '', $code);
        return $code;
    }

    private static function compileAsset($code): array|string|null
    {
        $replacement = '';
        return preg_replace('~\{\{\s*assert\(([^)]*)\)\s*}}~ism', $replacement . "$1", $code);
    }
}