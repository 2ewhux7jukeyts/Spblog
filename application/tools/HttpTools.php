<?php


namespace app\tools;


use app\admin\model\login;
use think\Request;
use think\Response;
use think\response\Json;

class HttpTools
{
    /**
     * @param Request $request
     * @param string $name
     * @param int $default
     * @return int
     * Get Post Val Int
     */
    public static function intpost(Request $request,string $name,int $default=0){
        $num = intval($request->post($name) ?? $default);
        return $num;
    }

    /**
     * @param Request $request
     * @param string $name
     * @param int $default
     * @return int
     *  Get Get Val Int
     */
    public static function intget(Request $request,string $name,int $default=0){
        $num = intval($request->get($name) ?? $default);
        return $num;
    }

    /**
     * @param Request $request
     * @param string $name
     * @param string $default
     * @return string
     * Get Post Val Safety
     */
    public static function SafeStrPost(Request $request,string $name,string $default=""){
        $inpu = $request->post($name) ?? $default;
        if(is_string($inpu)){
            if(trim($inpu) == ""){
                return $default;
            }

            $num = htmlentities($inpu);
            $num = trim($num);
            return $num;

        }
        return $default;
    }

    /**
     * @param Request $request
     * @param string $name
     * @param string $default
     * @return string
     *  Get Get Val Safety
     */
    public static function SafeStrGet(Request $request,string $name,string $default=""){
        $inpu = $request->get($name) ?? $default;
        if(is_string($inpu)){
            if(trim($inpu) == ""){
                return $default;
            }

            $num = htmlentities($inpu);
            $num = trim($num);
            return $num;

        }
        return $default;
    }

    /**
     * Deprecate
     * @param Request $request
     * @param string $name
     * @param int $default
     * @return int
     */
    public static function SafeIntGet(Request $request,string $name,int $default=0){
        $inpu = $request->get($name) ?? $default;
        if(is_string($inpu)){
            if(trim($inpu) == ""){
                return $default;
            }
        }
        $num = intval($inpu);
        return $num;
    }

    /**
     * @param $e
     * @param string $addon
     * @return array
     * Get Execption msg From Execption Class obj
     */
    public static function ErrorJson($e,$addon = ""){
        return [
            "status"=>false,
            "msg"=>$e->getMessage(),
            "path"=>$e->getFile(),
            "line"=>$e->getLine(),
            "addon"=>$addon
        ];
    }

    /**
     * Deprecate
     * @param $e
     * @return array
     */
    public static function OkJson($e){
        return [
            "status"=>true,
            "data"=>json_encode($e),
        ];
    }

    /**
     * Deprecate
     * @param $e
     * @return array
     */
    public static function faliJson($e){
        return [
            "status"=>false,
            "data"=>json_encode($e),
        ];
    }

    /**
     * @param $code
     * @param $msg
     * @param $count
     * @param $data
     * @return array
     * Ret Table in Layui Format
     */
    public static function RespLay($code,$msg,$count,$data){
        return [
                "code"=>$code,
                "msg"=>$msg,
                "count"=>$count,
                "data"=>$data
            ];
    }

    /**
     * @param string $var
     * @param string $data
     * @param bool $raw
     * @param string $typ
     * @param int $code
     * @return Response
     * JsonP response
     */
    public static function JsonpVar(string $var,string $data,bool $raw = false,string $typ = "let",int $code = 200){
        /**
         * atob("222wee")
         * "Ûm°y"
         * btoa("222wee")
         * "MjIyd2Vl"
         */
        $typ = htmlentities($typ);
        $var = htmlentities($var);

        if ($raw){
            $datap = "$typ $var = $data";
        }else{
            $data = base64_encode(trim($data));
            $datap = "$typ $var = atob(\"$data\")";
        }
        return Response::create(
            $datap
            ,''
            ,$code
            ,$header=[
            "Content-Type"=>"application/javascript"
        ]
        );
    }

}