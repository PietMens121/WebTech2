<?php

namespace App\Templating;

class Render
{
    private const VIEW_FOLDER_PATH = 'resources/views';

    /**
     * The function to initialize the view
     *
     * @param $filename
     * @param $data
     * @return void
     */
    public static function view($filename, $data = array()): void
    {
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
        $tempDir = $_SERVER['DOCUMENT_ROOT'] . '/temp';
        $temp = tempnam($tempDir, 'TMP_');
        $code = self::includeFile($filename);
        $code = self::compileView($code);
        file_put_contents($temp, '<?php class_exists(\'' . __CLASS__ . '\') or exit; ?>' . PHP_EOL . $code);
        return $temp;
    }

    /**
     * included files are properly included here
     *
     * @return string|bool
     */

    private static function includeFile($filename): string|bool
    {
        return file_get_contents(dirname(__DIR__, 2) . '/' . self::VIEW_FOLDER_PATH . '/' . $filename);
    }

    /**
     * changes all templating function names to correct code
     *
     * @param $code
     * @return mixed
     */
    private static function compileView($code): mixed
    {
        // TODO change templateing to correct code
        $code = self::compileEcho($code);
        $code = self::compileIf($code);
        $code = self::compileEndIf($code);
        $code = self::compileElseIf($code);
        $code = self::compileElse($code);
        return $code;
    }

    private static function compileEcho($code){
        return preg_replace('~{{\s*(.+?)\s*}}~is', '<?php echo $1 ?>', $code);
    }

    private static function compileIf($code){
        return preg_replace('~@if([^;]*).~is', '<?php if$1: ?>', $code);
    }

    private static function compileElseIf($code){
        return preg_replace('~@elseif([^;]*).~is', '<?php elseif$1: ?>', $code);
    }

    private static function compileElse($code){
        return preg_replace('~@else;~is', '<?php else: ?>', $code);
    }

    private static function compileEndIf($code){
        return preg_replace('~@endif[^;]*.~is', '<?php endif; ?>', $code);
    }


}