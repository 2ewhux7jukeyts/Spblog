<?php


namespace app\tools;


class Arrtools
{
    /**
     * @param $key
     * @param array $arr
     * @return bool
     * Test If in Arrkey
     */
    public static function array_key_exists($key,$arr){
        return in_array($key,array_keys($arr));
    }

    /**
     * @param $arr
     * @param $get
     * @param $def
     * @return mixed
     * get thins from an array if exist ,else retutn default
     */
    public static function getdef($arr,$get,$def){
        if ($arr == null){
            return $def;
        }
        if (self::array_key_exists($get,$arr)){
            return $arr[$get];
        }else{
            return $def;
        }
    }

    /**
     * @param $arr
     * @param array $keys
     * @return mixed
     * get Val from multi layers
     */
    public static function loopfetch($arr,array $keys){
        $tmp = self::getdef($arr,$keys[0],null);
        foreach (array_slice($keys,1) as $v){
            $tmp = self::getdef($tmp,$v,null);
        }
        return $tmp;
    }

    /**
     * @param array $in
     * @param array $std
     * @return array
     * only val in std can out
     */
    public static function greparr(array $in,array $std){
        $res = [];
        foreach ($in as $k=>$item) {
            if (in_array($k,$std)){
                $res[$k] = $item;
            }
        }
        return $res;
    }
}