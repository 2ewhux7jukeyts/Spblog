<?php
namespace app\admin\controller;



use app\admin\model\user;
use app\admin\tools\PrivCtl;
use app\Common\Conf\WordDict;
use app\Common\model\Menu;


class Index extends PrivCtl
{
    private function dorend($temp="index",$pagectx=[]){
        return $this->fetch($temp,[
            "titel"=>Menu::AdminTitle(),
            "ipaddr"=>$_SERVER["REMOTE_ADDR"],
            "Logon"=>"BlogAdmin",
            "sidebarlistadmin"=>Menu::adminMenu(),
            "infolist"=>[
                [
                    "title"=>"Welcome"
                    ,"text"=>$this->whoami["user"]["uname"]." IP:".$_SERVER["REMOTE_ADDR"]
                ]
            ],
            "pagectx"=>$pagectx
        ]);
    }

    public function index()
    {
        return $this->dorend("index"
        ,[
                "list"=>[
                    ["item"=>"phpinfo","value"=>PHP_VERSION]
                    ,["item"=>"ipaddr","value"=>$_SERVER["REMOTE_ADDR"]]
                    ,["item"=>"whoami","value"=>$this->whoami["user"]["uname"]]
                    ,["item"=>"UID","value"=>$this->whoami["user"]["uid"]]
                    ,["item"=>"Blogs","value"=>(new \app\Common\model\content())->counttab()]
                    ,["item"=>"Users","value"=>(new user())->counttab()]
                    ,["item"=>"BlogVersion","value"=>"0.1"]
                ],
                "dashtitle"=>"dashboard",
                "dashinfo"=>"dashboard view",
                "webtitle"=>"testadmin",
                "adminBAnner" => Menu::AdminBaner()
            ]
        );
    }

    public function user()
    {
        return $this->dorend("user",[
            "dashtitle"=>"user",
            "webtitle"=>"testadmin",
            "dashinfo"=>"user view",
        ]);
    }

    public function content()
    {
        return $this->dorend("content",[
            "dashtitle"=>"content",
            "webtitle"=>"testadmin",
            "dashinfo"=>"content view",
        ]);
    }

    public function commonts()
    {
        return $this->dorend("commonts",[
            "dashtitle"=>"commonts",
            "webtitle"=>"testadmin",
            "dashinfo"=>"commonts view",
        ]);
    }

    
}
