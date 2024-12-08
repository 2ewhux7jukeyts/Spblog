<?php
namespace app\index\controller;

use app\Common\model\Menu;
use think\Controller;

class Index extends Controller
{

    private function dorend($temp="index",$pagectx=[]){
        return $this->fetch($temp,[
            "Logon"=>Menu::var_Logon(),
            "phpinfo"=>PHP_VERSION,
            "titel"=>Menu::Title(),
            "ipaddr"=>$_SERVER["REMOTE_ADDR"],
            "sidebarlist"=>Menu::index(),
            "infolist"=>Menu::SlogenIndex(),
            "pagectx"=>$pagectx
        ]);
    }

    public function index()
    {
        return $this->dorend("index",[
            "dashinfo" => "Home Page",
            "dashtitle" => "Index",
            "Banner"=>Menu::Baner()
        ]);
    }

    public function content()
    {
        return $this->dorend("content",
            [
                "dashinfo" => "Blog Page",
                "dashtitle" => "Blog"
            ]
            );
    }

    public function cryptoaes()
    {
        return $this->dorend("crypto",

            [
                "dashinfo" => "Crypto Page",
                "dashtitle" => "Crypto"
            ]
            );
    }


    public function cryptourl()
    {
        return $this->dorend("cryptourl",

            [
                "dashinfo" => "Crypto Page",
                "dashtitle" => "Crypto"
            ]
        );
    }

}
