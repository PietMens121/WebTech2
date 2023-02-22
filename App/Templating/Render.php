<?php

namespace App\Templating;

class Render
{
    private const VIEW_FOLDER_PATH = 'resources/views';

    static function view($filename, $data = array())
    {
        $file = self::prepare($filename);
        require $file;
    }

    /**
     * @param $filename
     * @return false|string
     */
    static function prepare($filename)
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
     * included files are propperly included here
     *
     * @return mixed
     */

    static function includeFile($filename)
    {
        $contents = file_get_contents(dirname(__DIR__,2 ) . '/' . self::VIEW_FOLDER_PATH . '/' . $filename);
        return $contents;
    }

    /**
     * changes all templating function names to correct code
     *
     * @param $code
     * @return mixed
     */
    static function compileView($code)
    {
        // TODO change templateing to correct code

        return $code;
    }
}