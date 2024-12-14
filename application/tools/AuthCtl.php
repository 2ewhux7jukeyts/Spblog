<?php


namespace app\tools;


use app\Common\model\Privimodel;
use app\tools\Arrtools;
use app\tools\HttpTools;
use think\App;
use think\Controller;
use think\Exception;
use think\response\Json;
use think\Session;

class AuthCtl extends Controller
{
    public $whoami = null;
    public $uid = null;
    public $ctlname;
    public $funcname;

    function __construct(App $app = null)
    {
        parent::__construct($app);
        $this->whoami = json_decode(urldecode((new Session())->get("logindata")),true);

        $this->uid = Arrtools::loopfetch($this->whoami,["user","uid"]);
        $this->type = Arrtools::loopfetch($this->whoami,["user","type"]);
        $this->role = Arrtools::loopfetch($this->whoami,["user","role"]);

        $this->ctlname = $this->request->controller();
        $this->funcname = $this->request->action();
        if ($this->uid == null){
            if(!strpos($this->funcname,"r") && (!in_array($this->funcname,["tablecol"])))
            {
                die("UID IS NULL for ".urldecode($this->ctlname)." ".urldecode($this->funcname)." . <script> window.location='/';</script>");
            }
        }else{
            if (!(new Privimodel())->isadmin($this->role,$this->type)){
                die("ADMIN IS NULL for ".urldecode($this->ctlname)." ".urldecode($this->funcname)." . <script> window.location='/';</script>");
            }
        }

        //rbac check
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return Json::create(HttpTools::FaliJson(404),'',404);
    }

    protected function _tablecol($modle,string $jsonpvar,array $join=null){
        try{
            $cop = $modle;
            if ($join !== null) $cop[] = $join;
        }catch (Exception|\Throwable $t){
            return HttpTools::JsonpVar($jsonpvar,"");
        }
        return HttpTools::JsonpVar($jsonpvar,json_encode($cop),true);
    }
}