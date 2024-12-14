<?php


namespace app\Common\model;




use app\tools\Arrtools;
use app\tools\HttpTools;
use think\Exception;
use think\Model;

class BaseModel extends Model
{

    /**
     * O
     * @param int $size
     * @param int $index
     * @param array $field exclude
     * @return array
     * List data Of this Table in Layui Format
     */
    function listdatalay(int $size = 100,int $index = 0,array $field = []){
        $index = ($index - 1) * $size;
        $size = $size <= 0?0:$size;
        $index = $index <= 0?0:$index;
        $dataout = $this->limit($index,$size)->field($field,true)->select();
        $counts = $this->counttab();
        return HttpTools::RespLay(
            0,
            "",
            $counts,
            $dataout
        );
    }

    /**
     * O
     * @param string $field
     * @param string $name %like%
     * @param int $size
     * @param int $index
     * @return array
     */
    function find_feild_lay(string $field,string $name,int $size = 100,int $index = 0){
        $index = ($index - 1) * $size;
        $size = $size <= 0?0:$size;
        $index = $index <= 0?0:$index;
        $data = $this->whereLike(htmlentities($field),"%".htmlentities($name)."%")->limit($index,$size)->select();
        $cc = $this->whereLike(htmlentities($field),"%".htmlentities($name)."%")->count();
        return HttpTools::RespLay(
            0,
            "",
            $cc,
            $data
        );
    }


    /**
     * O
     * @param string $field
     * @param string $name Match
     * @param int $size
     * @param int $index
     * @return array
     */
    function find_feild_Match_lay(string $field,string $name,int $size = 100,int $index = 0){
        $index = ($index - 1) * $size;
        $size = $size <= 0?0:$size;
        $index = $index <= 0?0:$index;
        $data = $this->whereLike(htmlentities($field),htmlentities($name))->limit($index,$size)->select();
        $cc = $this->whereLike(htmlentities($field),htmlentities($name))->count();
        return HttpTools::RespLay(
            0,
            "",
            $cc,
            $data
        );
    }

    /**
     * O
     * @param $field
     * @param $cid
     * @return array|bool
     * Data only has One
     */
    function ltof($field,$cid){
        $retdata = $this->find_feild_lay($field,$cid);
        if($retdata["count"] === 1){
            return $retdata;
        }
        return false;
    }

    /**
     * O
     * @param string $field
     * @param string $name
     * @return bool
     * @throws \Exception
     * delect one condition
     */
    function del_wtf(string $field,string $name){
        if($name == "" or $name == null){
            return false;
        }
        return $this->where($field,"=",htmlentities($name))->delete();
    }

    /**
     * O
     * @param array $arr
     * @return bool
     * @throws \Exception
     * delect Muilti condition
     */
    function del_wtf_arr(array $arr){
        if($arr == null){
            return false;
        }
        $Temp = $this->where($arr[0][0],"=",$arr[0][1]);
        foreach (array_slice($arr,1) as $value){
            $Temp = $Temp->where($value[0],"=",$value[1]);
        }
        return $Temp->delete();
    }

    /**
     * O
     * @param array $arr
     * @return BaseModel|bool
     * Trans Array to where Condition
     */
    function where_arr(array $arr){
        if($arr == null){
            return false;
        }
        $Temp = $this->where($arr[0][0],"=",$arr[0][1]);
        foreach (array_slice($arr,1) as $value){
            $Temp = $Temp->where($value[0],"=",$value[1]);
        }
        return $Temp;
    }

    /**
     * O
     * @return BaseModel
     * Count  This  Table Row
     */
    function counttab(){
        return $this->count();
    }

    /**
     * O
     * @return BaseModel
     * Count  This  Table Row on One Condition
     */
    function count_wtf(string $field,string $name){
        if($name == "" or $name == null){
            return false;
        }
        return $this->where($field,htmlentities($name))->count();
    }


    /**
     * O
     * @param $field
     * @param $data
     * @param $wfield
     * @param $when
     * @return bool
     * UPdate One
     */
    public function updateonefiled($field,$data,$wfield,$when){
        return $this->save(
            [$field=>$data]
            ,
            [$wfield=>$when]
        );
    }


    /**
     * @param array $fields
     * @param string $table
     * @param array $fieldsb
     * @param string $tableb
     * @param string|null $whereis
     * @param int $index
     * @param int $size
     * @return array
     * Link Table Query
     */
    function queryfieldtablejoin(
        array $fields,
        string $table,
        array $fieldsb,
        string $tableb,
        string $whereis = null,
        int $index =0,
        int $size=10
    ){
        $index = ($index - 1) * $size;
        $index = $index <= 0?0:$index;
        $querysql = "";
        $cc = "";
        try{
            $fieldjoinsa = "";
            foreach ($fields as $k=>$v) {
                $v = htmlentities($v);
                $fieldjoinsa.="$v,";
            }
            $fieldjoinsa = trim($fieldjoinsa,",");
            $fieldjoinsa = trim($fieldjoinsa);

            $fieldjoinsb = "";
            foreach ($fieldsb as $k=>$v) {
                $v = htmlentities($v);
                $fieldjoinsb.="$table.$v=$tableb.$v,";
            }
            $fieldjoinsb = trim($fieldjoinsb,",");
            $fieldjoinsb = trim($fieldjoinsb);

            $cc = "select count(*)";
            $cc .= " from $table";
            $cc .= " INNER JOIN $tableb";
            $cc .= " ON $fieldjoinsb ";
            if($whereis !== null){
                $cc .= " ".$whereis." ";
            }
            $cc .= " ;";

            $ccd = $this->query($cc);

            $querysql = "select $fieldjoinsa";
            $querysql .= " from $table";
            $querysql .= " INNER JOIN $tableb";
            $querysql .= " ON $fieldjoinsb";
            if($whereis !== null){
                $querysql .= " ".$whereis." ";
            }
            $querysql .= " LIMIT $index,$size ;";

            $querydata = $this->query($querysql);

            return HttpTools::RespLay(
                0,
                "",
                $ccd[0]["count(*)"],
                $querydata
            );
        }catch (\Throwable|Exception $t){
            return HttpTools::RespLay(
                1,
                "",
                0,
                []
            );
        }

    }

    function tablecol(){
        return $this->query("SHOW COLUMNS FROM ".$this->getTable().";");
    }

    function tabclum($exclude = []){
        $resarr = [];
        foreach ($this->tablecol() as $k=>$v){
            $field = Arrtools::getdef($v,"Field",false);
            if($field != false){
                if(is_string($field)){
                    if (in_array($field,$exclude)){
                        continue;
                    }
                    if(strpos($field,"id") != false){
                        $resarr[] = [
                            "field"=>$field,
                            "fixed"=>"left",
                            "title"=>$field,
                            "sort"=>true
                        ];
                    }else{
                        $resarr[] = [
                            "field"=>$field,
                            "title"=>$field,
                        ];
                    }
                }else{
                    return false;
                }
            }
            continue;
        }
        return $resarr;
    }

    public function input_arrdata(
        array $arrin
        , array $mustkey = null
        , array $conditions = null
        , array $limitarr = null
        , bool $safe = false
    ){
        if ($limitarr != null && count($limitarr) != 0){

            if (count($arrin) != count($limitarr)){
                return false;
            }

            foreach ($arrin as $k=>$v){
                if (!in_array($k,$limitarr)) {
                    return false;
                };
            }
        }
        if ($mustkey != null && count($mustkey) != 0) {
            foreach ($mustkey as $k=>$v){
                foreach ($v["op"] as $ko=>$vo) {
                    switch ($ko) {
                        case "nrf": { // not exsist ret false
                            if (!in_array($k,array_keys($arrin))){return false;}
                        }break;
                        case "nm": { // not exsist make data
                            if (!in_array($k,array_keys($arrin))){
                                $arrin[$k] = $vo;
                            }
                        }break;
                        case "enf": { // exsist value null ret false
                            if (!in_array($k,array_keys($arrin))){return false;}
                            if ($arrin[$k] === null){return false;}
                        }break;
                        case "esmf": { // exsist value string empty ret false
                            if (!in_array($k,array_keys($arrin))){return false;}
                            if ($arrin[$k] === null){return false;}
                            if (is_string($arrin[$k]) && trim($arrin[$k]) == ""){return false;}
                        }break;
                        case "esmm": { // exsist value string empty make data
                            if (in_array($k,array_keys($arrin))){break;}
                            if ($arrin[$k] === null){break;}
                            if (is_string($arrin[$k]) && trim($arrin[$k]) == ""){
                                $arrin[$k] = $vo;
                            }
                        }break;
                        case "esfd": { // exsist value string make data
                            if (!in_array($k,array_keys($arrin))){break;}
                            $arrin[$k] = $vo;
                        }break;
                        case "esd": { // make data
                            $arrin[$k] = $vo;
                        }break;
                        case "drop": { // drop key from arr

                            if (!array_key_exists($k,$arrin)){break;}
                            unset($arrin[$k]);
                        }break;
                        case "esmd": {
                            if (!in_array($k,array_keys($arrin))){break;}
                            if ($arrin[$k] === null){break;}
                            if (is_string($arrin[$k]) && trim($arrin[$k]) == ""){
                                unset($arrin[$k]);
                            }
                        }break;
                        case "safe": { // special charset return false
                            if (!in_array($k,array_keys($arrin))){break;}
                            if ($arrin[$k] === null){break;}
                            if (is_string($arrin[$k]) && trim($arrin[$k]) == ""){break;}
                            if (preg_match('/[^\w ]+/i',$arrin[$k])) {
                                return false;
                            }
                        }break;
                        case "htmlen": { // html encode if exsit str val
                            if (!in_array($k,array_keys($arrin))){break;}
                            if ($arrin[$k] === null){break;}
                            if (is_string($arrin[$k]) && trim($arrin[$k]) == ""){break;}
                            if (is_string($arrin[$k])){

                                $arrin[$k] = htmlentities($arrin[$k]);
                            }
                        }break;
                        case "add": {
                            $arrin[$vo["k"]]=$vo["v"];
                        }break;
                        case "call": { // make more data using func
                            if (!in_array($k,array_keys($arrin))){break;}
                            $arrin[$k] = $vo($arrin[$k]);
                        }break;
                        case "callarr": { // make more data arr using func
                            if (!in_array($k,array_keys($arrin))){break;}
                            $arrin[$k] = $vo($arrin);
                        }break;
                        case "callf": { // make more data arr using func
                            if (!in_array($k,array_keys($arrin))){break;}
                            $retdata =  $vo($arrin[$k]);
                            if ($retdata === false){
                                return false;
                            }
                            $arrin[$k] = $retdata;
                        }break;
                        case "callarrf": { // make more data arr using func
                            if (!in_array($k,array_keys($arrin))){break;}
                            $retdata =  $vo($arrin);
                            if ($retdata === false){
                                return false;
                            }
                            $arrin[$k] = $retdata;
                        }break;
                        default :{
                            if (!in_array($k,array_keys($arrin))){return false;}
                        }
                    }
                }
            }
        }


        if ($safe) {
            $procarr = [];
            foreach ($arrin as $k => $v) {
                if (is_string($v)) {
                    $procarr[htmlentities($k)] = htmlentities($v);
                } else {
                    $procarr[htmlentities($k)] = $v;
                }
            }

            if ($conditions != null && count($conditions) != 0) {
                return $this->where_arr($conditions)->update($procarr);
            }else {
                return $this->save($procarr);
            }

        }else{
            if ($conditions != null && count($conditions) != 0){
                return $this->where_arr($conditions)->update($arrin);
            }else{
                return $this->save($arrin);
            }
        }


    }

}