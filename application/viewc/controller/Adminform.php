<?php
namespace app\admin\controller;



use app\admin\tools\PrivCtl;
use app\Common\Conf\WordDict;
use app\tools\Arrtools;
use app\tools\EncryptTool;
use app\tools\HttpTools;
use think\Request;

class Form extends PrivCtl
{

    private function form($tpl,$form)
    {
        return $this->fetch($tpl,array_merge(["webtitle"=>"testAdmin"],$form));
    }

    public function addpage()
    {
        return $this->form("contentform",
            [
                "list"=>[
                    [
                        "lable"=>"title",
                        "name"=>"title",
                        "type"=>"text"
                    ]
                    ,[
                        "lable"=>"content",
                        "name"=>"content",
                        "type"=>"markdowntext"
                    ]
                    ,[
                        "lable"=>"permid",
                        "name"=>"permid",
                        "type"=>"number"
                    ]

                ],
                "mdtag"=>"content-test-editormd-view",
                "formid"=>EncryptTool::gen_uuid(),
                "api"=>"/admin/content/C",
                "fetch_api"=>"",
                "uid"=>$this->uid,
                "cid"=>""
            ]
        );
    }

    public function editpage(Request $request)
    {
        $cid = HttpTools::SafeStrGet($request,"cid");
        return $this->form("contentform",
            [
                "list"=>[
                    [
                        "lable"=>"title",
                        "name"=>"title",
                        "type"=>"text"
                    ]
                    ,[
                        "lable"=>"content",
                        "name"=>"content",
                        "type"=>"markdowntext"
                    ],[
                        "lable"=>"permid",
                        "name"=>"permid",
                        "type"=>"number"
                    ]
                ],
                "mdtag"=>"content-test-editormd-view",
                "formid"=>EncryptTool::gen_uuid(),
                "fetch_api"=>"/admin/content/R",
                "api"=>"/admin/content/U",
                "uid"=>$this->uid,
                "cid"=>$cid
            ]
        );
    }

    public function viewpage(Request $request)
    {
        $cid = HttpTools::SafeStrGet($request,WordDict::$cid);
        $res = (new \app\Common\model\content())
            ->where_arr([
                [WordDict::$uid,$this->uid]
                ,[WordDict::$cid,$cid]
            ])->select()->toArray();
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
                "uid"=>$ct[WordDict::$uid],
                "title"=>$ct[WordDict::$title],
                "c_time"=>$ct[WordDict::$c_time],
                "l_time"=>$ct[WordDict::$l_time],
                "cid"=>$cid,
                "formid"=>EncryptTool::gen_uuid()
            ]
        );
    }

    public function edituser(Request $request)
    {
        $uid = HttpTools::SafeStrGet($request,"uid");
        return $this->form("adminuserform",
            [
                "list"=>[
                    [
                        "lable"=>WordDict::$uname,
                        "name"=>WordDict::$uname,
                        "type"=>"text"
                    ]
                    ,[
                        "lable"=>WordDict::$passwd,
                        "name"=>WordDict::$passwd,
                        "type"=>"password"
                    ]
                    ,[
                        "lable"=>"repasswd",
                        "name"=>"repasswd",
                        "type"=>"password"
                    ]
                    ,[
                        "lable"=>WordDict::$type,
                        "name"=>WordDict::$type,
                        "type"=>"number"
                    ]
                    ,[
                        "lable"=>WordDict::$role,
                        "name"=>WordDict::$role,
                        "type"=>"number"
                    ]
                ],
                "formid"=>EncryptTool::gen_uuid(),
                "api"=>"/admin/Userctl/U",
                "obj"=>base64_encode(
                    json_encode(
                        [
                            "uid"=>$uid
                        ]
                    )
                )
            ]
        );
    }

    public function editcomid(Request $request)
    {
        $comid = HttpTools::SafeStrGet($request,WordDict::$comid);
        return $this->form("commontsform",
            [
                "list"=>[
                    [
                        "lable"=>WordDict::$content,
                        "name"=>WordDict::$content,
                        "type"=>"textarea"
                    ]
            
                ],
                "formid"=>EncryptTool::gen_uuid(),
                "api"=>"/admin/Commctl/U",
                "obj"=>base64_encode(
                    json_encode(
                        [
                            "comid"=>$comid,
                            "pcid"=>"",
                            "cid"=>"",
                        ]
                    )
                )
            ]
        );
    }


    public function Recomid(Request $request)
    {
        $comid = HttpTools::SafeStrGet($request,WordDict::$comid);
        $cid = HttpTools::SafeStrGet($request,WordDict::$cid);
        return $this->form("commontsform",
            [
                "list"=>[
                    [
                        "lable"=>WordDict::$content,
                        "name"=>WordDict::$content,
                        "type"=>"textarea"
                    ]
            
                ],
                "formid"=>EncryptTool::gen_uuid(),
                "api"=>"/admin/Commctl/Re",
                "obj"=>base64_encode(
                    json_encode(
                        [
                            "cid"=>$cid,
                            "pcid"=>$comid,
                        ]
                    )
                )
            ]
        );
    }



}
