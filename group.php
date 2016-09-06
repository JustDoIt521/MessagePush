<?php
header("Content_Type:text/html;charset=utf8");
require_once("connect.php");
require_once("mysql.php");
$data=json_decode(file_get_contents("php://input"),true);
$what=$data["what"];
switch($what)
{
	case"createGroup":
		groupCreate();
		break;
	case"insertGroup":
		addMember();
		break;
}
function groupCreate()//create group  
{
	global $pdo,$data;
	$username=$data["username"];
	$groups=findGroups($username);
	$num=count($groups);
	if($num==0)
	{
		$group=$groups."group".$num;
		createGroup($group);
	}
	else
	{
		$sql="select max(id) from '$groups'";
		$res=$pdo->query($sql);
		$res=$res->fetch();
		$num=$res[0];
		$group=$groups."group".$num;
		createGroup($group);
	}
}