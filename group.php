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
	/*case"mygroups":
		mygroups();
		break;
		*/
	case"searchGroup":
		searchGroup();
		break;
	default:
		echo $what;
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
function askjoin()                      
{
	global $pdo,$data;
	$username=$data["username"];
	$groupID=$data["groupID"];
	$sql="select * from grouplist where id='$groupID'";         //to get the group's owner
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
	$pdo->exec($sql);
	$array=array("message"=>"0");
	echo json_encode(array("data"=>$array));
}
function showGroups()                           /**/
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
function sendMessage()                            
{
	global $pdo,$data;
	$temp=$data["groups"];    //all need to send message groups' id
	$group=explode("_",$temp);
	$num=findnum($group);
	$content=$data["content"];
	$time=date('y-m-d H:i:s',time());
	for($i=0;$i<$num;$i++)
	{
		$table=$group[$i]."message";
		$sql="insert into '$table' values
			('$content','$time')";
		$pdo->query($sql);
	}
	$array=array("message"=>"0");
	echo json_encode(array("data"=>$array));
	
}
function joinGroup()
{
	global $pdo,$data;
	$result=$data["result"];
	$groupName=$data["groupName"];   
	$groupID=$data["groupID"];
	$people=$data["people"];
	if($result=="yes")
	{
		$sql="insert into $groupId values
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
		member varchar(1000) not null 
		)ENGINE=InnoDB DEFAULT CHARSET=utf8";
	$pdo->exec($sql);                           // create the group to save all memebers
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