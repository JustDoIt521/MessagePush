<?php
header("Content_Type:text/html;charset=utf8");
require("connect.php");
$data=json_decode(file_get_contents("php://input"),true);
$what=$data['what'];
switch($what)
{
	case"login";
	login();
	break;
	cae"register";
	register();
	break;
	default:
	echo "error message";
}
function register()
{
	global $pdo,$data;
	$username=$data["username"];
	$sql="select * from userlist where name='$username'";
	$res=$pdo->query($sql);
	$res=$res->fetch();
	if(empty($res))
	{

	}
	else
	{
		$array=array("message"=>"1","point"=>"该用户名已存在");
		echo json_encode(array("data"=>$array));
	}
}
function login()
{
	global $pdo,$data;
	$username=$data["username"];
	$sql="select * from userlist where name='$username'";
	$sql=addslashes($sql);
	$res=$pdo->query($sql);
	$res=$res->fetch();
	if(!empty($res))
	{
		if($data["password"]==$res["password"])
		{
			$array=array("message"=>"0");
			echo json_encode(array("data"=>$array));
		}
	}
	else
	{
		$array=array("message"=>"1","point"=>"用户名或密码错误");
		echo json_encode(array("data"=>$array));
	}
}