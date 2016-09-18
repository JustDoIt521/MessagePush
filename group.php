<?php
header("Content_Type:text/html;charset=utf8");
require_once("connect.php");
require_once("mysql.php");
$data=json_decode(file_get_contents("php://input"),true);
$what=$data["what"];
switch($what)
{
	case"createGroup":
		createGroup();
		break;
	case"joinGroup":
		joinGroup();
		break;
	case"sendMessage":
		sendMessage();
		break;
	case"showGroups":
		showGroups();
		break;
	case"requireJoin":
		requireJoin();
		break;
	case "showMymessage":
		showMymessage();
		break;
	case"searchGroup":
		searchGroup();
		break;
	case"quitGroup":
		quitGroup();
		break;
	case"deleteGroup":
		deleteGroup();
		break;
	default:
		echo $what;
}
function deleteGroup()
{
	global $pdo,$data;
	$groupID=$data["groupID"];
	$sql="select * from $groupID";
	$res=$pdo->query($sql);
	$res=$res->fetchAll();
	$num=count($res);
	for($i=0;$i<$num;$i++)
	{
		$sql=
	}
}
function quitGroup()    /*ok*/
{
	global $pdo,$data;
	$username=$data["username"];
	$groupID=$data["groupID"];
	$sql="delete from $groupID where member='$username'";
	$pdo->exec($sql);
	$sql="select * from userlist where name='$username'";
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$mygroup=$res["mygroups"];
	$sql="delete from $mygroup where groupID='$groupID'";
	$pdo->exec($sql);
	$array=array("message"=>0);
	echo json_encode(array("data"=>$array));
}
function showMymessage()  /*ok*/
{
	global $pdo,$data;
	$username=$data["username"];
	$sql="select * from userlist where name='$username'";
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$myMessage=$res["mymessage"];
	$sql="select * from $myMessage where type!='1'";
	$res=$pdo->query($sql);
	$res=$res->fetchAll();
	print_r(json_encode(array("data"=>$res)));
}
function searchGroup()   /*ok*/
{
	global $pdo,$data;
	$search=$data["search"];
	$sql="select * from grouplist where groupName='$search'";
	$res1=$pdo->query($sql);
	$res1=$res1->fetchAll();
	$sql="select * from grouplist where id='$search'";
	$res2=$pdo->query($sql);
	$res2=$res2->fetchAll();
	$array=array_merge($res1,$res2);
	print_r(json_encode(array("data"=>$array)));
}
function requireJoin()        /*ok*/                 
{
	global $pdo,$data;
	$username=$data["username"];
	$groupID=$data["groupID"];
	$sql="select * from grouplist where id='$groupID'";         //to get the group's owner
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$owner=$res["owner"];
	$groupName=$res["groupName"];
	$sql="select * from userlist where name='$owner'";              //to get the owner's message table
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$myMessage=$res["mymessage"];
	$num=maxnum($myMessage,"id");
	$time=date('y-m-d H:i:s',time());
	$sql="insert into $myMessage (people,time,groupName,groupID,type,id) values
			('$username','$time','$groupName','$groupID','0','$num')";
	$pdo->exec($sql);
	$array=array("message"=>"0");
	echo json_encode(array("data"=>$array));
}
function showGroups()                           /*ok*/
{
	global $pdo,$data;
	$username=$data["username"];
	$sql="select * from userlist where name='$username'";    //select user's groups 
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$mygroups=$res["mygroups"];
	$sql="select * from $mygroups";        //get his all groups
	$array=$pdo->query($sql);
	$array=$array->fetchAll();
	print_r(json_encode(array("data"=>$array)));
}
function sendMessage()                            /*ok*/
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
		$sql="insert into $table values
			('$content','$time')";
		$pdo->exec($sql);
	}
	$array=array("message"=>"0");
	echo json_encode(array("data"=>$array));
	
}
function joinGroup()   /*ok*/
{
	global $pdo,$data;
	$result=$data["result"];
	$groupName=$data["groupName"];   
	$groupID=$data["groupID"];
	$people=$data["people"];
	$messageID=$data["messageID"];
	$username=$data["username"];
	$sql="select * from userlist where name='$people'";
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$mygroup=$res["mygroups"];
	if($result=="yes")
	{
		$sql="insert into $groupID values
			('$people')";
		$pdo->exec($sql);
		$sql="insert into $mygroup values
			('$groupName','$groupID','1')";
		$pdo->exec($sql);
	}
	$sql="select * from userlist where name='$username'";
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$myMessage=$res["mymessage"];	
	$sql="update $myMessage set type='1' where id='$messageID'";                //mark the message readed
	$pdo->exec($sql);
	$sql="select * from userlist where name='$people'";
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$mymessage=$res["mymessage"];
	$num=maxnum($mymessage,"id");
	$time=date('y-m-d H:i:s',time());
	$sql="insert into $mymessage values
		('$people','$time','$groupName','$groupID','$num','0','$result')";          //tell the people whether he is in
	$pdo->exec($sql);
	$array=array("message"=>0);
	echo json_encode(array("data"=>$array));
}

function createGroup()        /* ok*/                    
{
	global $pdo,$data;
	$groupName=$data["groupName"];
	$num=maxnum("grouplist","num");   		 //select the max number + 1 to be id
	$id="group".$num;
	$owner=$data["username"];
	$sql="insert into grouplist values    
		('$groupName','$owner','$id','$num')";  //add the new group into grouplist
	$pdo->exec($sql);
	$sql="create table $id           				
		(
		member varchar(1000) not null,
		mymessage varchar(1000) not null,
		mygroup varchar(1000) not null 
		)ENGINE=InnoDB DEFAULT CHARSET=utf8";
	$pdo->exec($sql);                           // create the group to save all memebers
	$sql="insert into $id values ('$owner')";
	$pdo->query($sql);
	$name=$id."message";
	createMessage($name);              //create the table to save all the host send's message
	$array=array("message"=>"0");
	echo json_encode(array("data"=>$array));
	$name=$data["username"];				//insert into mygroups the new create group
	$sql="select * from userlist where name='$name'";
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$mygroups=$res["mygroups"];
	$sql="insert into $mygroups values 
			('$groupName','$id','0')";
	$pdo->exec($sql);
}