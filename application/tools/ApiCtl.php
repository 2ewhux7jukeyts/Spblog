<?php


namespace app\tools;


use think\Controller;
use think\Exception;
use think\Response;

class ApiCtl extends Controller
{
    /**
     * @param $modle
     * @param string $jsonpvar
     * @param array|null $join
     * @return Response
     * Auto Gen Table colums for Table component Layui
     */
    protected function _tablecol($modle,string $jsonpvar,array $join=null){
        try{
            $cop = $modle;
            if ($join !== null) $cop[] = $join;
        }catch (Exception|\Throwable $t){
            return HttpTools::JsonpVar($jsonpvar,"");
        }
        return HttpTools::JsonpVar($jsonpvar,json_encode($cop),true);
    }
}