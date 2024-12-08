<?php


namespace app\Common\model;


class Privimodel
{
    private $usertype;
    private $itemtipy;

    private array $Ucando = [
        "admin"=>[
            "type"=>"122",
            "role"=>"1114"
        ]
    ];

    public function isadmin($role,$type){
        if ($role == $this->Ucando["admin"]["role"]){
            if ($type == $this->Ucando["admin"]["type"]){
                return true;
            }
        }
        return false;
    }
}