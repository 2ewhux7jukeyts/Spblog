<?php
if(!function_exists("readline")) {
    function readline($prompt){
        if($prompt){
            echo $prompt;
        }
        $fp = fopen("php://stdin","r");
        $line = rtrim(fgets($fp, 1024));
        return $line;
    }
}

function pdoobj($host,$port,$user,$passwd,$db = ""){
    try{
        if ($db !== ""){
            $dsn = "mysql:host=$host;port=$port;dbname=$db";

        }else{
            $dsn = "mysql:host=$host;port=$port;";
        }
        $dbh = new PDO($dsn, $user, $passwd);
        return $dbh;
    }catch (Exception $e){
        exit($e->getMessage());
    }
}

function dbs($dbh){
    $sql = "SHOW DATABASES;";
    $sth = $dbh->prepare($sql);
    $sth-> execute();
    $dbs = [];
    foreach($sth as $row) {
        $dbs[] = $row["Database"];
    }
    return $dbs;
}

function tables($dbh,$db){
    $sql = "SHOW TABLES FROM $db;";
    $sth = $dbh->prepare($sql);
    $sth-> execute();
    $dbs = [];
    foreach($sth as $row) {
        print_r($row);
    }
}

function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function cdb($dbh){
    $dbname = "";
    while (true){
        $tm = readline("Database:");
        if ($tm === "skip")break;
        $dbname = $tm;
        if(in_array($dbname,dbs($dbh))){
            continue;
        }
        $db = htmlentities($dbname);
        $sql_db = "CREATE DATABASE $db;";
        $sth = $dbh->prepare($sql_db);
        $sth-> execute();
        if(in_array($dbname,dbs($dbh))){
            echo "C db $dbname ok";
            echo "\n";
        }else{
            $serr =  "C db $dbname Error";
            exit($serr);
        }

    }
    return $dbname;
}

function cAdmin($dbh,$db,$uname,$passwd){
    $uid = gen_uuid();
    $T = date('Y-m-d H:i:s');
    $cu = <<<EOF
        INSERT INTO $db.`user`
        (uid, uname, passwd, `type`, `role`, c_time, l_time, ipaddr)
        VALUES('$uid', :name, :pwd, 122, 1114, '$T', '$T', '127.0.0.1');
EOF;
    $sth = $dbh->prepare($cu);
    $sth ->bindValue(':name', $uname);
    $sth ->bindValue(':pwd', $passwd);
    $sth-> execute();

}

function ctable($dbh,$db){

    echo "Cuser\n";
    echo "\n";
    $user = <<<EOF
        CREATE TABLE `user` (
          `uid` varchar(255) NOT NULL DEFAULT '0',
          `uname` varchar(100) NOT NULL DEFAULT '0',
          `passwd` varchar(100) DEFAULT '0',
          `type` int(11) DEFAULT 0,
          `role` int(11) NOT NULL DEFAULT 0,
          `c_time` timestamp NULL DEFAULT NULL,
          `l_time` timestamp NULL DEFAULT NULL,
          `ipaddr` varchar(100) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    EOF;
    $sth = $dbh->prepare($user);
    echo $sth-> execute();

    echo "Ccontent\n";
    $content = <<<EOF
        CREATE TABLE `content` (
          `cid` varchar(255) NOT NULL DEFAULT '0',
          `uid` varchar(255) DEFAULT '0',
          `permID` int(11) DEFAULT 200,
          `title` text NOT NULL DEFAULT 0,
          `content` text NOT NULL DEFAULT '0',
          `template` varchar(255) NOT NULL DEFAULT '0',
          `c_time` timestamp NULL DEFAULT NULL,
          `l_time` timestamp NULL DEFAULT NULL,
          `ipaddr` varchar(100) DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
    EOF;
    $sth = $dbh->prepare($content);
    $sth-> execute();

    $comments = <<<EOF
        CREATE TABLE `commont` (
            `comid` varchar(100) DEFAULT NULL,
            `uid` varchar(100) DEFAULT NULL,
            `pcid` varchar(100) DEFAULT NULL,
            `cid` varchar(100) DEFAULT NULL,
            `content` varchar(255) DEFAULT NULL,
            `c_time` timestamp NULL DEFAULT NULL,
            `l_time` timestamp NULL DEFAULT NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

    EOF;
    $sth = $dbh->prepare($comments);
    $sth-> execute();

    tables($dbh,$db);
}


function checkpdo(){
    if(extension_loaded("pdo")){
        return 1;
    }
    exit("PDO not exist");
    return 0;
}

echo "=========INSTALL==========";
echo "\n";
echo "=========CHECK_ENV========";
echo "\n";
echo "CHECK PDO:".checkpdo();
echo "\n";
echo "=========Db_connect========";
echo "\n";
if (!file_exists("./install.json")){
    $host = readline("Host:");
    $port = readline("Port:");
    $user = readline("User:");
    $passwd = readline("Passwd:");
    $dbh = pdoobj($host,$port,$user,$passwd); // will exist
    file_put_contents("./install.json",json_encode([
        "host"=>$host,
        "port"=>$port,
        "user"=>$user,
        "passwd"=>$passwd
    ]));
}else{
    $jobj = json_decode(file_get_contents("./install.json"));
    $dbh = pdoobj($jobj->host,$jobj->port,$jobj->user,$jobj->passwd);
}
echo "=========Create_Db========";
echo "\n";
$dbname = cdb($dbh);
echo "\n";
echo "=========Create_Table=====";
echo "\n";
echo "relink to $dbname";
echo "\n";
$jobj = json_decode(file_get_contents("./install.json"));
$dbh = pdoobj($jobj->host,$jobj->port,$jobj->user,$jobj->passwd,$dbname);
ctable($dbh,$dbname);
echo "\n";
echo "===========C_data==============";
echo "\n";
echo "relink to $dbname";
echo "\n";
$uname = md5(time() . "");
$passwdu = md5(random_bytes(22));
cAdmin($dbh,$dbname,$uname,$passwdu);
file_put_contents("./user.json",json_encode(["user"=>$uname,"passwd"=>$passwdu]));
echo "===========OK==============";
echo "\n";

