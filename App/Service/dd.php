<?php

namespace App\Service;

class dd
{
    static function d($data){
        if(is_null($data)){
            $str = "<i>NULL</i>";
        }elseif($data == ""){
            $str = "<i>Empty</i>";
        }elseif(is_array($data)){
            if(count($data) == 0){
                $str = "<i>Empty array.</i>";
            }else{
                $str = "<table style=\"border-bottom:0px solid #000;text-align:center;\" cellpadding=\"0\" cellspacing=\"0\">";
                foreach ($data as $key => $value) {
                    $str .= "<tr style=\"width:100%; \" ><td style=\"background-color:#008B8B; color:#FFF;border:1px solid #000;padding: 3px;\">" . $key . "</td><td style=\"border:1px solid #000; text-align: center; \">" . self::d($value) . "</td></tr>";
                }
                $str .= "</table>";
            }
        }elseif(is_resource($data)){
            while($arr = mysql_fetch_array($data)){
                $data_array[] = $arr;
            }
            $str = d($data_array);
        }elseif(is_object($data)){
            $str = d(get_object_vars($data));
        }elseif(is_bool($data)){
            $str = "<i>" . ($data ? "True" : "False") . "</i>";
        }else{
            $str = $data;
            $str = preg_replace("/\n/", "<br>\n", $str);
        }
        return $str;
    }

    static function dnl($data){
        echo self::d($data) . "<br>\n";
    }

    public static function dd($data){
        echo self::dnl($data);
        exit;
    }

    static function ddt($message = ""){
        echo "[" . date("Y/m/d H:i:s") . "]" . $message . "<br>\n";
    }
}