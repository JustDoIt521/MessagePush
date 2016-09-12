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
	case"joinGroup":
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
function joinGroup()
{
	global $pdo,$data;
	$type=$data["type"];
	$groupName=$data["groupName"];
	$groupID=$data["groupID"];
	$people=$data["people"];
	if($type=="yes")
	{
		$sql="insert into '$groupId' values
			('$people')";
		$pdo->exec($sql);
	}
	$sql="select * from userlist where name='$people'";
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$mymessage=$res["mymessage"];
	$num=maxnum($mymessage,"id");
	$sql="insert into '$mymessage'(people,groupName,id,type,result) values
		('$people','$groupName','$num','0','$result')";
	$pdo->exec($sql);
}
function askjoin()                      //
{
	global $pdo,$data;
	$username=$data["username"];
	$groupID=$data["groupID"];
	$sql="select * from grouplist where id='$groupID'";         //to get the group's owner
	$sql=addslashes($sql);
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$owner=$res["owner"];
	$groupName=$res["name"];
	$sql="select * from userlist where name='$owner'";              //to get the owner's message table
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$myMessage=$res["mymessage"];
	$num=maxnum($myMessage,"id");
	$time=data('y-m-d H:i:s',time());
	$sql="insert into '$mymessage'(people,time,groupName,groupID,type,id) values
			('$username','$time','$groupName','$groupID','$num','0')";
	$sql=addslashes($sql);
	$pdo->exec($sql);
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
	$temp=$data["groups"];    //all need to send message groups' id
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
function createGroup()                          //
{
	global $pdo,$data;
	$groupName=$data["groupName"];
	$sql="select max(number) from grouplist";  //select the max number + 1 to be id
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$num=$res[0]+1;
	$id="group".$num;
	$owner=$data["username"];
	$sql="insert into grouplist values    
		('$groupName','$owner','$id','$num')";  //add the new group into grouplist
	$sql=addslashes($sql);
	$pdo->exec($sql);
	$sql="create table '$id'
		(
		member varchar(1000) not null 
		)";										//create the group to save all memebers
	$sql=addslashes($sql);  
	$pdo->exec($sql);
	if(checkReturn())
	{
		$name=$id."message";
		createMessage($name);              //create the table to save all the host send's message
		if(checkReturn())
		{
			$array=array("message"=>"0");
			echo json_encode(array("data"=>$array));
		}
	}
	$name=$data["username"];				//insert into mygroups the new create group
	$sql="select * from userlist where name='$name'";
	$sql=addslashes($sql);
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$mygroups=$res["mygroups"];
	$sql="insert into '$mygroups' values 
			('$groupName','$id','0');"
	$sql=addslashes($sql);
	$pdo->query($sql);
}