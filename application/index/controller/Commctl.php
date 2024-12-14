<?php


namespace app\index\controller;

use app\tools\AuthCtl;
use app\admin\tools\PrivCtl;
use app\Common\Conf\WordDict;
use app\Common\model\commont;
use app\tools\EncryptTool;
use app\tools\HttpTools;
use Exception;
use think\Request;
use think\response\Json;

class Commctl extends AuthCtl{
    

    public function L_R(Request $request){
        return Json::create((new \app\Common\model\content())->queryfieldtablejoin(
            ["commont.uid","user.uname","commont.cid","commont.pcid","commont.content","commont.comid","commont.c_time","commont.l_time"],
            "commont",
            ["uid"],
            "user",
            "where commont.uid=\"".urlencode($this->uid).'"',
            HttpTools::intget($request,"page"),
            HttpTools::intget($request,"limit",10) % 100));
    }

    public function F_R(Request $request){
        return Json::create((new \app\Common\model\content())->queryfieldtablejoin(
            ["commont.uid","user.uname","commont.cid","commont.pcid","commont.content","commont.comid","commont.c_time","commont.l_time"],
            "commont",
            ["uid"],
            "user",
            "where commont.cid=\"".urlencode(HttpTools::SafeStrGet($request,WordDict::$cid)).'"',
            HttpTools::intget($request,"page"),
            HttpTools::intget($request,"limit",10) % 100));
    }

    public function C(Request $request){
        try{
            $commont = new commont();
            $res = $commont->input_arrdata(
                [
                    "uid"=>$this->uid,
                    "pcid"=>"",
                    "cid"=>HttpTools::SafeStrPost($request,WordDict::$cid),
                    "content"=>HttpTools::SafeStrPost($request,WordDict::$content),
                    "comid"=>EncryptTool::gen_uuid(),
                    "c_time"=>date('Y-m-d H:i:s'),
                    "l_time"=>date('Y-m-d H:i:s'),
                ]
                ,
                [
                    "cid"=>[
                        "op"=>[
                            "esmd"=>"",
                            "nrf"=>"",
                            "esmf"=>""
                        ]
                    ],
                    "content"=>[
                        "op"=>[
                            "esmd"=>"",
                            "nrf"=>"",
                            "esmf"=>""
                        ]
                    ]
                ]
            );
            return Json::create($res);
        }catch (\Throwable|Exception $e){
            return Json::create(
                HttpTools::ErrorJson($e)
            );
        }
    }

    public function U(Request $request){
        $comid = HttpTools::SafeStrPost($request,WordDict::$comid);
        try{
            $commont = new commont();
            $res = $commont->input_arrdata(
                [
                    "content"=>HttpTools::SafeStrPost($request,WordDict::$content),
                    "l_time"=>date('Y-m-d H:i:s'),
                ]
                ,
                [
                    "content"=>[
                        "op"=>[
                            "esmd"=>"",
                            "nrf"=>"",
                            "esmf"=>""
                        ]
                    ]
                ]
                ,
                [
                    [WordDict::$comid,$comid]
                ]
            );
            return Json::create($res);
        }catch (\Throwable|Exception $e){
            return Json::create(
                HttpTools::ErrorJson($e)
            );
        }
    }

    public function Re(Request $request){
        $comid = HttpTools::SafeStrPost($request,WordDict::$pcid);
        try{
            $commont = new commont();
            $res = $commont->input_arrdata(
                [

                    "uid"=>$this->uid,
                    "pcid"=>$comid,
                    "cid"=>HttpTools::SafeStrPost($request,WordDict::$cid),
                    "content"=>HttpTools::SafeStrPost($request,WordDict::$content),
                    "comid"=>EncryptTool::gen_uuid(),
                    "c_time"=>date('Y-m-d H:i:s'),
                    "l_time"=>date('Y-m-d H:i:s'),
                ]
                ,
                [
                    "content"=>[
                        "op"=>[
                            "esmd"=>"",
                            "nrf"=>"",
                            "esmf"=>""
                        ]
                        ],
                    "pcid"=>[
                        "op"=>[
                            "esmd"=>"",
                            "nrf"=>"",
                            "esmf"=>""
                        ]
                        ],
                    "cid"=>[
                        "op"=>[
                            "esmd"=>"",
                            "nrf"=>"",
                            "esmf"=>""
                        ]
                    ],
                ]
                
            );
            return Json::create($res);
        }catch (\Throwable|Exception $e){
            return Json::create(
                HttpTools::ErrorJson($e)
            );
        }
    }

    public function D(Request $request){
        $commont = new commont();
        return Json::create(
            $commont->del_wtf_arr(
                [
                    [WordDict::$comid,HttpTools::SafeStrPost($request,WordDict::$comid)]
                ]
            )
        );
    }

    public function tablecol(){
        $cop = (new \app\Common\model\commont())->tabclum(
            ["cid","uid","pcid","c_time"]
        );
        $cop[] = ["field"=> "uname", "title"=> "UserName"];
        return $this->_tablecol(
            $cop
            ,"coleee"
        );
    }

}