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

                ",
                "p_toolbarDemo"=>[
                    [
                        "type"=>"dropdown",
                        "name"=>"addon",
                        "event"=>"",
                        "id"=>"addon",
                        "funcs"=>[
                            "load('/index/commctl/F_R?cid=$cid')"
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
                    ],
                    [
                        "type"=>"single",
                        "name"=>"top",
                        "event"=>"",
                        "id"=>"top",
                        "funcs"=>[
                            "load('/index/commctl/F_R?cid=$cid')"
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
                    ],
                    [
                        "type"=>"single",
                        "name"=>"back",
                        "event"=>"",
                        "id"=>"back",
                        "funcs"=>[
                            "load('/index/commctl/F_R?cid=$cid')"
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
                    ],
                    [
                        "type"=>"single_row",
                        "name"=>"view",
                        "event"=>"view",
                        "id"=>"view",
                        "funcs"=>[
                            "load('/index/commctl/F_R?cid=$cid&comid='+obj.data.comid)"
                            ,""],
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

                    ,
                    [
                        "type"=>"single_row",
                        "name"=>"reply",
                        "event"=>"reply",
                        "id"=>"view",
                        "funcs"=>[""],
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

                    ,
                    [
                        "type"=>"single_row",
                        "name"=>"del",
                        "event"=>"del",
                        "id"=>"view",
                        "funcs"=>[""],
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