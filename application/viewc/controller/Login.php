<?php


namespace app\viewc\controller;


use app\tools\HttpTools;
use think\Controller;
use think\Request;
use think\response\Json;
use think\response\Redirect;
use think\Session;

class Login extends Controller
{
    public function index(){
        return $this->fetch("login");
    }

    public function dologin(Request $request){
        $user = HttpTools::SafeStrPost($request,"uname",false);
        $passwd = HttpTools::SafeStrPost($request,"passwd",false);

        session_start();
        if (\app\admin\model\login::checklogin()){
            return Json::create(HttpTools::OkJson("login ok!"));
        }
        if($user != false){
            if ($passwd != false){
                $login = new \app\admin\model\login($user,$passwd);
                if ($login->is_login()){
                    $login->sessiondata();
                    return Json::create(HttpTools::OkJson("login ok!"));
                }
            }
        }
        return Json::create(HttpTools::FaliJson("login faild"));
    }

    public function logout(){
        \app\admin\model\login::logout();
        return Redirect::create("/viewc/");
    }
}