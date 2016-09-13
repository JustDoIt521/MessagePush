<?php
/*$array=array("one"=>"1","two"=>"2");
print_r($array)."<br>";
$str=array("hahah"=>$array);
print_r($str);*/
require("connect.php");
$name="showtime";
/*$sql="create table $name
		(
		name varchar(1000) not null 
		)ENGINE=InnoDB DEFAULT CHARSET=utf8";*/
$sql="select * from userlist where name='lishang'";
$res=$pdo->query($sql);
$res=$res->fetch();
$password=$res["password"];
echo $password;
echo strlen($password)."<br>";
echo strcmp($password,"lishang");
//print_r($res);
//print_r($pdo->errorCode());
//print_r($pdo->errorInfo())
//print_r($pdo->errorInof());
//print_r($num);
/*$word="json_ecode_name";
$array=explode("_",$word);
print_r($array)."<br>";
echo count($array);*/
?>