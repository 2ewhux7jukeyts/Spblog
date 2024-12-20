<?php
namespace app\index\controller;

use think\Controller;
use think\response\Redirect;

class Index extends Controller
{

    public function index()
    {
        return Redirect::create("/viewc/");
    }

}
