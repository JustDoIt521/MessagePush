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
	case"insertMember":
		addMember();
		break;
	case"sendMessage":
		sendMessage();
		break;
	case"showGroups":
		showGroups();
		break;
	case"askjoin":
		askjoin();
		break;
	default:
		echo $what;
}
function askjoin()
{
	global $pdo,$data;
	$username=$data["username"];
	$groupID=$data["groupID"];
	$sql="select * from userlist where name='$username'";
	$sql=addslashes($sql);
	$pdo->exec($sdql);
	if(checkReturn())
	{
		$array=array("message"=>"0");
		echo json_encode(array("data"=>$array));
	}
}
function showGroups()
{
	global $pdo,$data;
	$username=$data["username"];
	$sql="select * from userlist where name='$username'";    //select user's groups 
	$sql=addslashes($sql);
	$res=$res->query($sql);
	$res=$res->fetch();
	$mygroups==$res["mygroups"];
	$sql="selec * from '$mygroups'";        //get his all groups
	$sql=addslashes($sql);
	$array=$pdo->query($sql);
	$array=$array->fetchAll();
	print_r(json_encode(array("data"=>$array)));
}
function sendMessage()
{
	global $pdo,$data;
	$temp=$data["groups"];    //all ned to send message groups' id
	$group=explode("_",$temp);
	$num=count($group);
	$content=$data["content"];
	$time=date('y-m-d H:i:s',time());
	for($i=0;$i<$num;$i++)
	{
		$table=$group[$i]."message";
		$sql="insert into '$table' values
			('$content','$time')";
		$sql=addslashes($sql);
		$pdo->query($sql);
	}
	$array=array("message"=>"0");
	echo json_encode(array("data"=>$array));
	
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
	$owner=$data["username"];
	$sql="insert into grouplist values    
		('$groupName','$owner','$id','$num')";  //add the new group
	$sql=addslashes($sql);
	$pdo->exec($sql);
	$sql="create table '$id'
		(
		member varchar(1000) not null, 
		)";										//create the group
	$sql=addslashes($sql);  
	$pdo->exec($sql);
	if(checkReturn())
	{
		$name=$id."message";
		createMessage($name);
		if(checkReturn())
		{
			$array=array("message"=>"0");
			echo json_encode(array("data"=>$array));
		}
	}
}