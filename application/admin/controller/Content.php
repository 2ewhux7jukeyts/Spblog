<?php


namespace app\admin\controller;

use app\admin\tools\PrivCtl;
use app\Common\Conf\WordDict;
use app\tools\Arrtools;
use app\tools\EncryptTool;
use app\tools\HttpTools;
use think\Request;
use think\Response;
use think\response\Json;

class Content extends PrivCtl
{
    /**
     * @param Request $request
     * @return Response
     * Join Table Query
     */
    public function L_R(Request $request){
        return Json::create((new \app\Common\model\content())->queryfieldtablejoin(
            ["content.uid","user.uname","content.permID","content.template","content.cid","content.title","content.c_time","content.l_time","content.ipaddr"],
            "content",
            ["uid"],
            "user",
            "where content.uid=\"".urlencode($this->uid).'"',
            HttpTools::intget($request,"page"),
            HttpTools::intget($request,"limit",10) % 100));
    }

    /**
     * @param Request $request
     * @return Response
     *  Join Table Query Title
     */
    public function T_R(Request $request){
        return Json::create((new \app\Common\model\content())->queryfieldtablejoin(
            ["content.uid","user.uname","content.permID","content.template","content.cid","content.title","content.c_time","content.l_time","content.ipaddr"],
            "content",
            ["uid"],
            "user",
            "where content.uid=\"".urlencode($this->uid).'" and content.title=\"'.HttpTools::SafeStrGet($request,"title"),
            HttpTools::intget($request,"page"),
            HttpTools::intget($request,"limit",10) % 100));
    }

    public function tablecol(){
        $cop = (new \app\Common\model\content())->tabclum();
        $cop[] = ["field"=> "uname", "title"=> "UserName"];
        return $this->_tablecol(
            $cop
            ,"coleee"
        );
    }

    /**
     * @param Request $request
     * @return Response
     * Table_colum
     */
    public function C(Request $request){
        $sta = false;
        if ($request->isPost()){
            $cot = new \app\Common\model\content();
            $sta = $cot->input_arrdata(
                Arrtools::greparr($request->post(),["content","title","permID"]),
                [
                    "content"=>[
                        "op"=>[
                            "esmf"=>""
                            ,"htmlem"=>""
                        ]
                    ]
                    ,"title"=>[
                        "op"=>[
                            "esmf"=>""
                            ,"htmlem"=>""
                        ]
                    ]
                    ,"permID"=>[
                        "op"=>[
                            "add"=>["k"=>"permID","v"=>HttpTools::intpost($request,"permid")]
                            ,"esmm"=>"600"
                        ]
                    ]
                    ,"permid"=>[
                        "op"=>[
                            "drop"=>""
                        ]
                    ]
                    ,"uid"=>[
                        "op"=>[
                            "esd"=>$this->uid
                        ]
                    ]
                    ,"cid"=>[
                        "op"=>[
                            "esd"=>EncryptTool::gen_uuid()
                        ]
                    ],
                    "l_time"=>[
                        "op"=>[
                            "add"=>["k"=>"l_time","v"=>date('Y-m-d H:i:s')]
                        ]
                    ],
                    "c_time"=>[
                        "op"=>[
                            "add"=>["k"=>"c_time","v"=>date('Y-m-d H:i:s')]
                        ]
                    ],
                    "ipaddr"=>[
                        "op"=>[
                            "add"=>["k"=>"ipaddr","v"=>$_SERVER["REMOTE_ADDR"]]
                        ]
                    ],
                    "template"=>[
                        "op"=>[
                            "add"=>["k"=>"template","v"=>"api"]
                        ]
                    ]
                ]
            );
        }
        return Json::create($sta);
    }

    /**
     * @param Request $request
     * @return Response
     * Update Content
     */
    public function U(Request $request){
        $sta = false;
        if ($request->isPost()){
            $cot = new \app\Common\model\content();
            $sta = $cot->input_arrdata(
                Arrtools::greparr($request->post(),["content","title","permid","permID"]),
                [
                    "content"=>[
                        "op"=>[
                            "esmd"=>""
                            ,"htmlem"=>""
                        ]
                    ]
                    ,"title"=>[
                        "op"=>[
                            "esmd"=>""
                            ,"htmlem"=>""
                        ]
                    ]
                    ,"permID"=>[
                        "op"=>[
                            "add"=>["k"=>"permID","v"=>HttpTools::intpost($request,"permid")]
                        ]
                    ]
                    ,"permid"=>[
                        "op"=>[
                            "drop"=>""
                        ]
                    ]
                    ,"uid"=>[
                        "op"=>[
                            "drop"=>""
                        ]
                    ]
                    ,"cid"=>[
                        "op"=>[
                            "drop"=>""
                        ]
                    ],
                    "l_time"=>[
                        "op"=>[
                            "add"=>["k"=>"l_time","v"=>date('Y-m-d H:i:s')]
                        ]
                    ],
                    "ipaddr"=>[
                        "op"=>[
                            "add"=>["k"=>"ipaddr","v"=>$_SERVER["REMOTE_ADDR"]]
                        ]
                    ],
                ],
                [
                    ["uid",$this->uid]
                    ,["cid",HttpTools::SafeStrPost($request,"cid")]
                ]
            );
        }

        return Json::create($sta);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws \Exception
     * Delect
     */
    public function D(Request $request){
        $result = false;

        if ($request->isPost()){
            $cid = HttpTools::SafeStrPost($request,WordDict::$cid);
            if (strpos($cid,"*") || trim($cid) === ""){
                return Json::create(false);
            }

            $result = (new \app\Common\model\content())->del_wtf_arr(
                [
                    [WordDict::$uid,$this->uid]
                    ,[WordDict::$cid,$cid]
                ]
            );
        }

        return Json::create($result);
    }

    /**
     * @param Request $request
     * @return Response
     * Read Content
     */
    public function R(Request $request){
        return Json::create(
            (new \app\Common\model\content())->where_arr(
                [
                    ["uid",$this->uid],
                    ["cid",HttpTools::SafeStrGet($request,"cid")]
                ]
            )->select()
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