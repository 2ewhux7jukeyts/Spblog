<?php


namespace app\viewc\controller;

use app\Common\Conf\WordDict;
use app\tools\EncryptTool;
use app\tools\HttpTools;
use app\tools\Layui;
use think\Controller;
use think\Request;
use think\response\Json;

class Indexform extends Controller
{
    private function form($tpl,$form)
    {
        return $this->fetch($tpl,array_merge(["webtitle"=>"testAdmin"],$form));
    }

    public function index(){
        return Json::create([]);
    }
    
    public function Commonts(Request $request){
        $cid = HttpTools::SafeStrGet($request,WordDict::$cid);
        return $this->form(
            "commontview",
            [
                "api"=>"/index/commctl/F_R?cid=$cid",
                "col"=>"/index/commctl/tablecol",
                "p_toolbarDemo"=>[
                    [
                        "type"=>"dropdown",
                        "name"=>"addon",
                        "event"=>"",
                        "id"=>"addon",
                        "funcs"=>[
                            Layui::tablereload()
                            ,Layui::layeropen2(
                                "obj.id==='add'",
                                "/index/form/addcomment?cid=$cid",
                                "add comment"
                            ),""],
                        "data"=>json_encode(
                            [
                                [
                                    "id"=>'reload',
                                    "title"=> 'Reload'
                                ],
                                [
                                    "id"=>'add',
                                    "title"=> 'ADD'
                                ]
                            ]
                        )
                    ]
                    
                ]
            ]
        );
    }


    public function addcomment(Request $request)
    {
        $cid = HttpTools::SafeStrGet($request,"cid");
        return $this->form("commentform",
            [
                "list"=>[
                    [
                        "lable"=>WordDict::$content,
                        "name"=>WordDict::$content,
                        "type"=>"text"
                    ]
                ],
                "formid"=>EncryptTool::gen_uuid(),
                "api"=>"/index/Commctl/C",
                "obj"=>base64_encode(
                    json_encode(
                        [
                            "cid"=>$cid
                        ]
                    )
                )
            ]
        );
    }
}