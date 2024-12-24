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
                "preloadin" => "
                var backlist = [];
                function delcomment(comid){
                    $.ajax({
                        url:'/index/Commctl/D',
                        data:{'comid':name},
                        type:'post',
                        success:function(data){
                            console.log(data);
                            layer.alert(JSON.stringify(data), {
                                title: data
                            });
                        },
                        error:function(data){
                            layer.alert(JSON.stringify(data), {
                                title: data
                            });
                        }
                    });
                }
                ",
                "p_toolbarDemo"=>[
                    [
                        "type"=>"dropdown",
                        "name"=>"addon",
                        "event"=>"",
                        "id"=>"addon",
                        "funcs"=>[
                            Layui::reload($cid)
                            ,Layui::layeropen2(
                                "obj.id==='add'",
                                "/viewc/indexform/addcomment?cid=$cid",
                                "add comment"
                            ),""],
                        "data"=>json_encode([
                            [
                                "id"=>'reload',
                                "title"=> 'Reload'
                            ],
                            [
                                "id"=>'add',
                                "title"=> 'ADD'
                            ]
                        ])
                    ],
                    [
                        "type"=>"single",
                        "name"=>"top",
                        "event"=>"top",
                        "id"=>"top",
                        "funcs"=>[Layui::top($cid)],
                        "data"=>json_encode([])
                    ],
                    [
                        "type"=>"single",
                        "name"=>"back",
                        "event"=>"back",
                        "id"=>"back",
                        "funcs"=>[Layui::backview($cid)],
                        "data"=>json_encode([])
                    ],
                    [
                        "type"=>"single_row",
                        "name"=>"view",
                        "event"=>"view",
                        "id"=>"view",
                        "funcs"=>[Layui::view($cid)],
                        "data"=>json_encode([])
                    ]
                    ,
                    [
                        "type"=>"single_row",
                        "name"=>"reply",
                        "event"=>"reply",
                        "id"=>"reply",
                        "funcs"=>[Layui::rep()],
                        "data"=>json_encode([])
                    ]
                    ,
                    [
                        "type"=>"single_row",
                        "name"=>"del",
                        "event"=>"del",
                        "id"=>"del",
                        "funcs"=>[Layui::del()],
                        "data"=>json_encode([])
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