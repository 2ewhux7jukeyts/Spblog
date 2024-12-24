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

    public static function view($cid){
        return "
        if(obj.event==='view'){
            backlist.push(obj.data.comid)
            load('/index/commctl/F_R?cid=$cid&comid='+obj.data.comid)
        }
        
        ";
    }

    public static function backview($cid){
        return "
        if(obj.event==='back'){
            if(backlist.length > 1){
                backlist.pop()
                load('/index/commctl/F_R?cid=$cid&comid='+backlist[backlist.length-1])

            }else{
                backlist = [];
                load('/index/commctl/F_R?cid=$cid')
            }
            
        }
        
        ";
    }

    public static function reload($cid){
        return "
        if(obj.id==='reload'){
            backlist = [];
            load('/index/commctl/F_R?cid=$cid')
        }";
        
    }

    public static function top($cid){
        return "
        if(obj.event==='top'){
            backlist = [];
            load('/index/commctl/F_R?cid=$cid')
        }";
        
    }

    public static function rep(){
        return "
            if(obj.event === 'reply'){
                console.log(data)
                layer.open({
                    title: 'Reply - comid:'+ data.comid+' Cid:'+data.cid,
                    type: 2,
                    area: ['80%','80%'],
                    content: '/viewc/adminform/Recomid?comid='+data.comid+'&cid='+data.cid
                });
            }

        ";
    }

    public static function del(){
        return "
            
            if(obj.event === 'del'){
                layer.confirm('DELETE? [comid: '+ data.comid +']  ', function(index){
                    // DEL DOM TR
                    // layer.close(index);
                    delcomment(data.comid)
                    obj.del(); 
                });
            }
        ";
    }

}
