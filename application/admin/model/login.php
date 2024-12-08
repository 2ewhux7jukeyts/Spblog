<?php


namespace app\admin\model;


use app\tools\Arrtools;
use app\tools\EncryptTool;
use think\Session;

class login
{
    public $user = null;
    public $ipadd = null;
    public $token = null;
    public $islogin = null;
    public $teststr = "684t95euryrg8uieyt8g73gft7o76yg1";
    public static $teststrsta = "684t95euryrg8uieyt8g73gft7o76yg1";
    public function __construct($name,$passwd)
    {
        if (!self::checklogin()){
            $uobj = new user();
            $userfind = $uobj->ltof("uname",$name);
            if ($userfind){
                $uro = $userfind["data"][0];
                if ($uro["passwd"]==$passwd){
                    $this->user = $uro;
                    $this->token = EncryptTool::gen_uuid();
                    $this->ipadd = $_SERVER["REMOTE_ADDR"];
                    $this->islogin = $this->teststr;

                    $uobj->updateonefiled(
                        "ipaddr",$this->ipadd,"uid",$uro["uid"]
                    );
                    $uobj->updateonefiled(
                        "l_time",date('Y-m-d H:i:s'),"uid",$uro["uid"]
                    );

                }
            }
        }
    }

    public function is_login()
    {
        return $this->islogin;
    }

    public function sessiondata(){
        $data = json_encode(
            [
                "islogin"=>$this->islogin,
                "user"=>$this->user,
                "ipaddr"=>$this->ipadd,
                "token"=>$this->token
            ]
        );
        (new Session())->set("logindata",urlencode($data));
        return $data;
    }

    public static function checklogin(){
        $data = (new Session())->get("logindata");
        $jdata = json_decode(urldecode($data),true);
        return (Arrtools::getdef($jdata,"islogin","") === self::$teststrsta);
    }

    public static function getdata(string $key){
        $data = (new Session())->get("logindata");
        $jdata = json_decode(urldecode($data),true);
        if (Arrtools::getdef($jdata,"islogin","") == self::$teststrsta){
            return Arrtools::getdef($jdata,$key,false);
        }
        return false;
    }

    public static function getall(){
        $data = (new Session())->get("logindata");
        $jdata = json_decode(urldecode($data),true);
        if (Arrtools::getdef($jdata,"islogin","") == self::$teststrsta){
            return $jdata;
        }
        return false;
    }

    public static function logout(){
        (new Session())->set("logindata",urlencode(""));
    }


}