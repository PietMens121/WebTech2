<?php

namespace App\Templating;

class Render
{
    private const VIEW_FOLDER_PATH = 'resources/views';
    private static string $cache_path = '';
    private static array $sections = [];
    private static bool $clearing_cache = true;

    /**
     * The function to initialize the view
     *
     * @param $filename
     * @param $data
     * @return void
     */
    public static function view($filename, $data = array()): void
    {
        self::$cache_path = $_SERVER['DOCUMENT_ROOT'] . '/temp';
        self::clear_cache();
        $file = self::prepare($filename);
        extract($data, EXTR_SKIP);
        require $file;
    }

    /**
     * @param $filename
     * @return false|string
     */
    private static function prepare($filename): bool|string
    {
//        creating temp file so the original doesnt change
        $temp = tempnam(self::$cache_path, 'TMP_');
        $code = self::includeFiles($filename);
        $code = self::compileView($code);
        file_put_contents($temp, '<?php class_exists(\'' . __CLASS__ . '\') or exit; ?>' . PHP_EOL . $code);
        return $temp;
    }

    private static function clear_cache(): void
    {
        if(self::$clearing_cache){
            foreach(glob(self::$cache_path . '/TMP_*') as $file){
                unlink($file);
            }
        }
    }

    /**
     * included files are properly included here
     *
     * @return string|bool
     */

    private static function includeFiles($filename): string|bool
    {
        $code = file_get_contents(dirname(__DIR__, 2) . '/' . self::VIEW_FOLDER_PATH . '/' . $filename);
        preg_match_all('~@(extends|@include)\(([^)]*)\)~is', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            $code = str_replace($value[0], self::includeFiles($value[2]), $code);
        }
        $code = preg_replace('~@(extends|@include)#(#([^)]*)#)#~is', '', $code);
        return $code;
    }

//preg_match_all('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
//foreach ($matches as $value) {
//$code = str_replace($value[0], self::includeFiles($value[2]), $code);
//}
//$code = preg_replace('/{% ?(extends|include) ?\'?(.*?)\'? ?%}/i', '', $code);
//return $code;

    /**
     * changes all templating function names to correct code
     *
     * @param $code
     * @return mixed
     */
    private static function compileView($code): mixed
    {
        $code = self::compileSection($code);
        $code = self::compileYield($code);
        $code = self::compileEcho($code);
        $code = self::compileIf($code);
        $code = self::compileEndIf($code);
        $code = self::compileElseIf($code);
        $code = self::compileElse($code);
        return $code;
    }

    private static function compileEcho($code)
    {
        return preg_replace('~{{\s*(.+?)\s*}}~is', '<?php echo $1 ?>', $code);
    }

    private static function compileIf($code)
    {
        return preg_replace('~@if([^;]*).~is', '<?php if$1: ?>', $code);
    }

    private static function compileElseIf($code)
    {
        return preg_replace('~@elseif([^;]*).~is', '<?php elseif$1: ?>', $code);
    }

    private static function compileElse($code)
    {
        return preg_replace('~@else;~is', '<?php else: ?>', $code);
    }

    private static function compileEndIf($code)
    {
        return preg_replace('~@endif[^;]*.~is', '<?php endif; ?>', $code);
    }

    private static function compileSection($code)
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

    private static function compileYield($code)
    {
        foreach (self::$sections as $section => $value) {
            $code = preg_replace('~@yield\(' . $section . '\)~is', $value, $code);
        }
        $code = preg_replace('~@yield\(([^)]*)\)~is', '', $code);
        return $code;
    }
}