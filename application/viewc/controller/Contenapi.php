<?php


namespace app\viewc\controller;


use app\tools\ApiCtl;
use app\tools\Arrtools;
use app\tools\EncryptTool;
use app\tools\HttpTools;
use think\Controller;
use think\Request;
use think\Response;
use think\response\Json;

class Contenapi extends ApiCtl
{
    
    public function viewpage(Request $request)
    {
        $res = (new \app\Common\model\content())->where_arr(
            [
                ["cid",HttpTools::SafeStrGet($request,"cid")],
                ["permID","666"]
            ]
        )->select()->toArray();
        $ct = Arrtools::getdef($res,0,
            [
                "uid"=>"",
                "title"=>"",
                "c_time"=>"",
                "l_time"=>"",
                "cid"=>"",
                "formid"=>""
            ]
        );
        return $this->fetch("mdview",
            [
                "uid"=>$ct["uid"],
                "title"=>$ct["title"],
                "c_time"=>$ct["c_time"],
                "l_time"=>$ct["l_time"],
                "cid"=>HttpTools::SafeStrGet($request,"cid"),
                "formid"=>EncryptTool::gen_uuid()
            ]
        );
    }

}