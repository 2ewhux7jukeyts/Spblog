<?php


namespace app\Common\model;


use app\Common\Conf\WordDict;

class Menu
{
    public static function index(){
        return [
            [
                "name"=>"index",
                "href"=>"/",
                "arr"=>"",
                "icotoptag"=>"am-icon-home"
            ],
            [
                "name"=>"blog",
                "icotoptag"=>"am-icon-cogs",
                "arr"=>[
                    [
                        "name"=>"blogs",
                        "href"=>"/index/index/content/",
                        "arr"=>"",
                    ]
                ],
            ]

            

        ];
    }

    public static function SlogenIndex(){
        return [
            [
                "title"=>"Msg"
                ,"text"=>"Welcome ".$_SERVER["REMOTE_ADDR"]
            ]
        ];
    }

    public static function Title(){
        return "Spblog";
    }

    public static function AdminTitle(){
        return "Spblog Admin";
    }

    public static function var_Logon(){
        return "Blog";
    }

    public static function adminMenu(){
        return [
            [
                "name"=>"index",
                "href"=>"/admin",
                "arr"=>"",
                "icotoptag"=>"am-icon-home"
            ],
            [
                "name"=>"user",
                "icotoptag"=>"am-icon-cogs",
                "arr"=>[
                    [
                        "name"=>"userManager",
                        "href"=>"/admin/index/user/",
                        "arr"=>"",
                    ]
                ],
            ],
            [
                "name"=>"contentManager",
                "icotoptag"=>"am-icon-cogs",
                "arr"=>[
                    [
                        "name"=>"Content",
                        "href"=>"/admin/index/content/",
                        "arr"=>"",
                    ],
                    [
                        "name"=>"commonts",
                        "href"=>"/admin/index/commonts/",
                        "arr"=>"",
                    ],
                ],
            ],
        ];
    }

    public static function AdminBaner(){
        return WordDict::$BannerAdmin;
    }

    public static function Baner(){
        return WordDict::$BannerBlog;
    }
}