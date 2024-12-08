<?php


namespace app\index\controller;


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
    public function index(Request $request){
        return Json::create((new \app\Common\model\content())->queryfieldtablejoin(
            ["content.uid","user.uname","content.permID","content.template","content.cid","content.title","content.c_time","content.l_time","content.ipaddr"],
            "content",
            ["uid"],
            "user",
            "where content.permID=\"666\"",
            HttpTools::intget($request,"page"),
            HttpTools::intget($request,"limit",10) % 100));
    }

    public function tablecol(){
        $cop = (new \app\Common\model\content())->tabclum(["content","permID","ipaddr","uid","cid","template"]);
        return $this->_tablecol(
            $cop
            ,"coleee"
            ,["field"=> "uname", "title"=> "UserName"]
        );
    }

    public function getone(Request $request){
        return Json::create(
            (new \app\Common\model\content())->where(
                "permID","=","666"
            )->where(
                "cid","=",HttpTools::SafeStrGet($request,"cid")
            )->select()
        );
    }

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

    public function fetchmd(Request $request)
    {
        $res = (new \app\Common\model\content())->where_arr(
            [
                ["cid",HttpTools::SafeStrGet($request,"cid")],
                ["permID","666"]
            ]
        )->select()->toArray();
        $ct = Arrtools::getdef($res,0,[]);
        return Arrtools::getdef($ct,"content","# NO ITEM");
    }

}