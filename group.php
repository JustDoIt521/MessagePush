<?php
header("Content_Type:text/html;charset=utf8");
require_once("connect.php");
//require_once("mysql.php");
$data=json_decode(file_get_contents("php://input"),true);
$what=$data["what"];
switch($what)
{
	case"createGroup":
		groupCreate();
		break;
	case"insertMember":
		addMember();
		break;
	case"sendMessage":
		sendMessage();
		break;
}
function sendMessage()
{
	global $pdo,$data;
	
}
function insertMember()
{
	global $pdo,$data;
	$groupID=$data["groupID"];
	$member=$data["member"];
	$sql="select message from userlist where name='$memeber'";    //search the people 
	$sql=addslashes($sql);
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$message=$res[0];                //select the member's message
	$sql="insert into '$groupID' values 
		('$memeber','$message')";
	$sql=addslashes($sql);
	$pdo->query($sql);
	if(checkReturn())
	{
		$array=array("message"=>"0");
		echo json_encode(array("data"=>$array));
	}
}
function createGroup()
{
	global $pdo,$data;
	$groupName=$data["groupName"];
	$sql="select max(number) from grouplist";  //select the max number to be id
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$num=$res[0]+1;
	$id="group".$num;
	$owner=$data["user"];
	$sql="insert into grouplist values    
		('$groupName','$owner','$id','$num')";  //add the new group
	$sql=addslashes($sql);
	$pdo->exec($sql);
	$sql="create table '$id'
		(
		member varchar(1000) not null,
		message varchar(1000) not null 
		)";										//create the group
	$sql=addslashes($sql);  
	$pdo->query($sql);
	if(checkReturn())
	{
		$array=array("message"=>"0");
		echo json_encode(array("data"=>$array));
	}
}