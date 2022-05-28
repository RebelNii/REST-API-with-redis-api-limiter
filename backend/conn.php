<?php
$server = "localhost";
$user = "Rebel";
$password = "";
$dbname = "bookstore";

//connect to server
$db = @new mysqli($server,$user,$password,$dbname);
//error handling
if($db->connect_error){
    exit("can't connect");
}




?>
