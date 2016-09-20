<?php
/*$array=array("one"=>"1","two"=>"2");
print_r($array)."<br>";
$str=array("hahah"=>$array);
print_r($str);*/
require("connect.php");
$name="lelel";
$sql="select * from userlist where id>2";
$res=$pdo->query($sql);
$res=$res->fetchAll();
if($res==NULL)
{
echo "error";
}
else
{
	//$res=$res->fetchAll();
print_r(array($name=>$res));
}
/*
$sql="select * from mygroups1";
$res=$pdo->query($sql);
$res=$res->fetchAll();
print_r($res[0]["groupName"]);*/
//$name="showtime";
/*$sql="create table $name
		(
		name varchar(1000) not null 
		)ENGINE=InnoDB DEFAULT CHARSET=utf8";*/
//$sql="select * from userlist where name='lishang'";
/*$type="num";
$table="grouplist";
$sql="select  max($type) from $table";
$res=$pdo->query($sql);
$res=$res->fetch();
if($res[0]==NULL)
{
	echo "1";
}
else
{
	print_r($res);
}*/
/*$password=$res["password"];
echo $password;
echo strlen($password)."<br>";
echo strcmp($password,"lishang");
*/
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