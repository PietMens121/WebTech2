<?php

//https://stackoverflow.com/questions/41669246/how-to-write-own-dd-function-same-as-laravel
//dump and die function from stack overflow
//only visuals

namespace App\Helpers;
class dd
{
     function d($data){
        if(is_null($data)){
            $str = "<i>NULL</i>";
        }elseif($data == ""){
            $str = "<i>Empty</i>";
        }elseif(is_array($data)){
            if(count($data) == 0){
                $str = "<i>Empty array.</i>";
            }else{
                $str = "<table style=\"border-bottom:0px solid #000;\" cellpadding=\"0\" cellspacing=\"0\">";
                foreach ($data as $key => $value) {
                    $str .= "<tr><td style=\"background-color:#008B8B; color:#FFF;border:1px solid #000;\">" . $key . "</td><td style=\"border:1px solid #000;\">" . $this->d($value) . "</td></tr>";
                }
                $str .= "</table>";
            }
        }elseif(is_resource($data)){
            while($arr = mysql_fetch_array($data)){
                $data_array[] = $arr;
            }
            $str = $this->d($data_array);
        }elseif(is_object($data)){
            $str = $this->d(get_object_vars($data));
        }elseif(is_bool($data)){
            $str = "<i>" . ($data ? "True" : "False") . "</i>";
        }else{
            $str = $data;
            $str = preg_replace("/\n/", "<br>\n", $str);
        }
        return $str;
    }

    function dnl($data){
        return $this->d($data) . "<br>\n";
    }



    function ddt($message = ""){
        echo "[" . date("Y/m/d H:i:s") . "]" . $message . "<br>\n";
    }
}