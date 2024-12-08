<?php


namespace app\index\controller;


use app\tools\EncryptTool;
use app\tools\HttpTools;
use think\Controller;
use think\Exception;
use think\Request;
use think\response\Json;

class Cryptor extends Controller
{
    public function index(){
        return Json::create([]);
    }

    public function Aesde(Request $request){
        if (!$request->isPost()){return Json::create([]);}
        try{
            $res = EncryptTool::aesde_192_ECB(
                HttpTools::SafeStrPost($request,"cypher")
                ,HttpTools::SafeStrPost($request,"key")
                ,HttpTools::SafeStrPost($request,"vi")
                ,HttpTools::SafeStrPost($request,"method",'aes-192-ecb')
            );
            return Json::create(HttpTools::OkJson(urldecode($res)));
        }catch (Exception|\Throwable $t){
            return Json::create(
                HttpTools::ErrorJson($t)
            );
        }
    }

    public function Urlde(Request $request){
        if (!$request->isPost()){return Json::create([]);}
        try{
            return Json::create(HttpTools::OkJson(urldecode(HttpTools::SafeStrPost($request,"cypher"))));
        }catch (Exception|\Throwable $t){
            return Json::create(
                HttpTools::ErrorJson($t)
            );
        }
    }

    public function Urlen(Request $request){
        if (!$request->isPost()){return Json::create([]);}
        try{
            return Json::create(HttpTools::OkJson(urlencode(HttpTools::SafeStrPost($request,"cypher"))));
        }catch (Exception|\Throwable $t){
            return Json::create(
                HttpTools::ErrorJson($t)
            );
        }
    }

    public function Aesen(Request $request){
        if (!$request->isPost()){return Json::create([]);}
        try{
            $res = EncryptTool::aesen_192_ECB(
                HttpTools::SafeStrPost($request,"cypher")
                ,HttpTools::SafeStrPost($request,"key")
                ,HttpTools::SafeStrPost($request,"vi")
                ,HttpTools::SafeStrPost($request,"method",'aes-192-ecb')
            );
            return Json::create(HttpTools::OkJson($res));
        }catch (Exception|\Throwable $t){
            return Json::create(
                HttpTools::ErrorJson($t)
            );
        }
    }

    public function Aesenb64(Request $request){
        if (!$request->isPost()){return Json::create([]);}
        try{
            $res = EncryptTool::aesen_192_ECB(
                HttpTools::SafeStrPost($request,"cypherb64")
                ,HttpTools::SafeStrPost($request,"key")
                ,HttpTools::SafeStrPost($request,"vi")
                ,HttpTools::SafeStrPost($request,"method",'aes-192-ecb')
            );
            return Json::create(HttpTools::OkJson($res));
        }catch (Exception|\Throwable $t){
            return Json::create(
                HttpTools::ErrorJson($t)
            );
        }
    }
}