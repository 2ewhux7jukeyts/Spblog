<?php


namespace app\tools;


class Layui
{
    public static function tablereload(){
        return "
        //table.reload app/tools/Layui
        if(obj.id=== 'reload' ){table.reload('test', {});}
        ";
    }

    public static function layeropen2($cond,$url,$title){
        
        return "
            if($cond){
                layer.open({
                        title: '$title',
                        type: 2,
                        area: ['80%','80%'],
                        content: '$url'
                    });
            }
        ";
    }

}
