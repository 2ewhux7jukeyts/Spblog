<?php
namespace app\admin\controller;

use app\admin\model\user;
use app\admin\tools\PrivCtl;
use app\Common\Conf\WordDict;
use app\tools\HttpTools;
use app\tools\EncryptTool;
use think\Exception;
use think\Request;
use think\response\Json;

class Userctl extends PrivCtl
{


    //CURD
    // R
    public function R(Request $request)
    {
        return Json::create(
            (new user())->listdatalay(
                HttpTools::intget($request,"limit") % 100,
                    HttpTools::intget($request,"page"),
                    ["passwd"]
            )
        );
    }

    // U
    public function U(Request $request)
    {
        try{
            $userin = new user();
            $res = $userin->input_arrdata(
                [
                    "uname"=>HttpTools::SafeStrPost($request,WordDict::$uname),
                    "passwd"=>HttpTools::SafeStrPost($request,WordDict::$passwd),
                    "type"=>HttpTools::SafeStrPost($request,WordDict::$type),
                    "role"=>HttpTools::SafeStrPost($request,WordDict::$role),
                    "ipaddr"=>$_SERVER["REMOTE_ADDR"],
                    "l_time"=>date('Y-m-d H:i:s'),
                ]
                ,
                [
                    "uname"=>[
                        "op"=>[
                            "esmd"=>"",
                            "callf"=>function($data){
                                if(strlen($data)<8){
                                    return false;
                                }
                                return $data;
                            }
                        ]
                    ],
                    "passwd"=>[
                        "op"=>[
                            "esmd"=>""
                        ]
                    ],
                    "role"=>[
                        "op"=>[
                            "esmd"=>""
                        ]
                    ],
                    "type"=>[
                        "op"=>[
                            "esmd"=>""
                        ]
                    ],
                ],
                [
                    ["uid",$this->uid]
                ]

            );
            return Json::create($res);
        }catch (\Throwable|Exception $e){
            return Json::create(
                HttpTools::ErrorJson($e)
            );
        }
    }
}
