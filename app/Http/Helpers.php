<?php
namespace App\Http;
use DateTime;
class Helpers {
    //helper convert date
    public static function  date_br($date)
    {
        if (!$date instanceof \DateTime) {
            $date = new \DateTime($date);
        }
        return $date->format('d-m-Y H:i');
    }
    public static function  date_universal($date)
    {
        if($date != NULL):
            $array=explode("/",$date);
            $rev=array_reverse($array);
            $date=implode("-",$rev);
            return $date;
        else:
            return null;
        endif;
    }
    public static function  date_db($date)
    {
        $formatdateA = substr_replace($date, '/', 2, 0);;
        $formatdateA = substr_replace($formatdateA, '/', 5, 0);;
        if($formatdateA != NULL):
            $array=explode("/",$formatdateA);
            $rev=array_reverse($array);
            $date=implode("-",$rev);
            return $date;
        else:
            return null;
        endif;
    }
    //helper format date time
    public static function  format_date($data = NULL)
    {
        if($data):
            $d = new DateTime($data);
            return $d->format('d/m/Y \à\s H:i:s');
        else:
            return date('Y-m-d H:i:s');
        endif;
    }
    //helper format inverse date time
    public static function  format_inversedate($data = NULL)
    {
        if($data != NULL):
            $array=explode("/",$data);
            $rev=array_reverse($array);
            $date=implode("-",$rev);
            return $date . ' '. date('H:i:s');
        else:
            return date('Y-m-d H:i:s');
        endif;
    }
    public static function  format_inversedateonly($data = NULL)
    {
        if($data != NULL):
            $array=explode("/",$data);
            $rev=array_reverse($array);
            $date=implode("-",$rev);
            return $date;
        else:
            return date('Y-m-d');
        endif;
    }
    public static function date_only_br($date)
    {
        if (!$date instanceof \DateTime) {
            $date = new \DateTime($date);
        }
        return $date->format('d/m/Y');
    }
    public static function date_mouth_day($date)
    {
        if (!$date instanceof \DateTime) {
            $date = new \DateTime($date);
        }
        return $date->format('d-m');
    }
    //helper generate token application
    public static function  gen_token() {
        return substr(md5(uniqid(rand(), true)), 0, 20);
    }
    //helper generate token number
    public static function  gen_token_number() {
        return rand(0,50000);
    }
    //helper convert money_br
    public static function  money_br($date)
    {
        return number_format($date, 2, ',', '.');
    }
    //helper convert money_reverse
    public static function  money_reverse($date)
    {
        $price = str_replace('.', '', $date);
        return  str_replace(',', '.', $price);
    }
    //helper convert decial
    public static function  decial($date)
    {
        $price = str_replace('.', '.', $date);
        return  floatval($price);
    }
    //
    public static function define_value()
    {
        return 20.00;
    }
    public static function commission_sale()
    {
        return 0.10;
    }
    public static function manager()
    {
        return 'MANAGER';
    }
    public static function define_domain()
    {
        return '@megabolao.club';
    }
}