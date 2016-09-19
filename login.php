<?php
header("Content_Type:text/html;charset=utf8");
require_once("connect.php");
require_once("mysql.php");
$data=json_decode(file_get_contents("php://input"),true);
$what=$data["what"];
switch($what)
{
	case"login":
		login();
		break;
	case"register":
		register();
		break;
	default:
		echo "error message";
}
function register()
{
	global $pdo,$data;
	$username=$data["username"];
	$sql="select count(*) from userlist where name='$username'";
	$res=$pdo->query($sql);
	$res=$res->fetch();
	if($res[0]==0)
	{
		$password=$data["password"];
		$num=findnum("userlist")+1;
		$mygroups="mygroups".$num;
		$message="myMessage".$num;
		$time=date('y-m-d H:i:s',time());
		$sql="insert into userlist values
			('$username','$password','$num','$mygroups','$message','$time')";
		$pdo->exec($sql);
		mygroups($mygroups);      //create a table to save all my groups  my own's and my join's
		message($message);       //creat a table to get message  (ask to join,refuse to join,allow to join)
		$array=array("message"=>"0");
		echo json_encode(array("data"=>$array));
	}
	else
	{
		$array=array("message"=>"1");
		echo json_encode(array("data"=>$array));
	}
}
function login()
{
	global $pdo,$data;
	$username=$data["username"];
	$sql="select * from userlist where name='$username'";
	$res=$pdo->query($sql);
	$res=$res->fetch();
	if(!empty($res))
	{
		if($res["password"]==$data["password"])
		{
			$array=array("message"=>"0");
			echo json_encode(array("data"=>$array));
		}else
		{
			$array=array("message"=>"1");
			echo json_encode(array("data"=>$array));
		}
	}
	else
	{
		$array=array("message"=>"1");
		echo json_encode(array("data"=>$array));
	}
}