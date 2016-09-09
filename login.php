<?php
header("Content_Type:text/html;charset=utf8");
require_once("connect.php");
require_once("mysql.php");
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
	$sql=addslashes($sql);	
	$res=$pdo->query($sql);
	if(empty($res))
	{
		$password=$data["password"];
		$num=count("userlist")+1;
		$mygroups="mygroups".$num;
		$sql="insert into userlist values
			('$username','$password	','$num','$mygroups')";
		$sql=addslashes($sql);
		$pdo->exec($sql);
		mygroups($mygroups);
		$addmembers="addmemebers".$num;
		addmemebers($addmembers);       //creat a table to get people who want to join
		$array=array("message"=>"0");
		echo json_encode(array("data"=>$array));
	}
	else
	{
		$array=array("message"=>"1");
		echo json_encode("data"=>$array);
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
		$array=array("message"=>"1");
		echo json_encode(array("data"=>$array));
	}
}