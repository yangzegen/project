<?php
$user = "yangzegen";
$password = "";
$host = "localhost";
$database = "askquestion";
$connection = mysqli_connect($host,$user,$password,$database);

if(!$connection){
  echo "connection error";
}
?>