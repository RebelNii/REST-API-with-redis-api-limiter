<?php
//connect to server
require_once("./conn.php");
require_once("./vendor/autoload.php");


header('Access-Control-Allow-Origin: http://localhost:5500');
header('Access-Control-Allow-Headers: x-api');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: Application/json');

if($_SERVER['REQUEST_METHOD']=='POST'){

    $email =addslashes($_POST["email"]);
    $apikey = password_hash($email, PASSWORD_DEFAULT);

    $sql = "INSERT INTO `api` SET `email`='$email', `api`='$apikey' ";

    $result = $db->query($sql);
    if(!$result){
        exit("SQL error");
    }else{
        echo json_encode(['status'=>'success','api'=>$apikey]);
    }

    $db->close();
}

if($_SERVER['REQUEST_METHOD']=='GET'){
    $userKey = $_SERVER['HTTP_X_API'];
    if($userKey == 'null'){
        echo json_encode(['status'=>'Access denied']);
        exit();
    }

    $redisClient = new Predis\Client();
    $cachedKey = $redisClient->get($userKey);


    if($cachedKey){
        $hitCount = $redisClient->incr($userKey);
        if($hitCount < 10){
            AccessTable($db);
        }else{
            echo json_encode(['status'=> 'Access denied']);
            exit();
        }
    }else{
        $sql = "SELECT * FROM `api` WHERE `api`='$userKey' ";
        $result = $db->query($sql);
        if($result->num_rows !== 0){
            $redisClient->incr($userKey);
            $redisClient->expire($userKey, 30);
            AccessTable($db);
            $result->free();
        }else{
            echo json_encode(['status'=> 'Access denied']);
            exit();
        }
    }

}

function AccessTable($db){
    
    $sql = "SELECT `email` FROM `api`";
    $result = $db->query($sql);
    $row = array();
    while($array = $result->fetch_array())
        array_push($row, $array);
        echo json_encode($row);
        exit();
    
}









?>