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
	case"showMembers":
		showmembers();
		break;
	case"newMessage":
		newMessage();
		break;
	case"updateLogin":
		updateLogin();
		break;
	default:
		echo $what;
}
function updateLogin()
{
	global $pdo,$data;
	$username=$data["username"];
	$time=date('y-m-d H:i:s',time());
	$sql="update userlist set  lastLogin='$time' where name='$username'";
	$pdo->exec($sql);
}
function newMessage()
{
	global $pdo,$data;
	$username=$data["username"];
	$time=loginTime($username);
	$groupID=$data["groupID"];
	$num=haveMessage($groupID,$time);
	$array=array("message"=>$num);
	echo json_encode(array("data"=>$array));
}
function showMembers()
{
	global $pdo,$data;
	$groupID=$data["groupID"];
	$sql="select * from $groupID";
	$res=$pdo->query($sql);
	$res=$res->fetchAll();
	print_r(json_encode(array("data"=>$res)));
}
function deleteGroup()			/*ok*/
{
	global $pdo,$data;
	$username=$data["username"];
	$groupID=$data["groupID"];
	$groupName=$data["groupName"];
	$sql="select * from $groupID";
	$res=$pdo->query($sql);
	$res=$res->fetchAll();
	$num=count($res);
	$time=date('y-m-d H:i:s',time());
	$id=0;
	$type="deleteGroup";
	for($i=0;$i<$num;$i++)
		{
			$mygroups=$res[$i]["mygroup"];
			$sql="delete from $mygroups where groupID='$groupID'";
			$pdo->exec($sql);
			$mymessage=$res[$i]["mymessage"];
			$sql="insert into $mymessage values 
				('$username','$time','$groupName','$groupID','$id','$type','0','$type')";
			$pdo->exec($sql);
		}
	$sql="delete from grouplist where id='$groupID'";
	$pdo->exec($sql);
	$groupName=$groupID."message";
	$sql="drop table $groupName";
	$pdo->exec($sql);
	$sql="drop table $groupID";
	$pdo->exec($sql);
	$array=array("message"=>0);
	echo json_encode(array("data"=>$array));
}
function quitGroup()    /*ok*/          //exit the group
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
	$sql="select * from $myMessage where reading!='1'";
	$res=$pdo->query($sql);
	$res=$res->fetchAll();
	print_r(json_encode(array("data"=>$res)));
	$sql="update $myMessage set reading=1 where reading!=1";     //make these message to be readed
	$pdo->exec($sql);
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
	$type="askJoin";
	$sql="insert into $myMessage (people,time,groupName,groupID,type,reading,id) values
			('$username','$time','$groupName','$groupID','$type','0','$num')";
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
	$sql="select * from $mygroups";        //get  groups  he created and joined
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
	$mymessage=$res["mymessage"];
	$type="refuseJoin";
	if($result=="yes")
	{
		$type="agreeJoin";
		$sql="insert into $groupID values
			('$people','$mymessage','$mygroup')";
		$pdo->exec($sql);
		$sql="insert into $mygroup values
			('$groupName','$groupID','1')";
		$pdo->exec($sql);
	}
	$sql="select * from userlist where name='$username'";
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$myMessage=$res["mymessage"];	
	$sql="update $myMessage set reading='1' where id='$messageID'";                //mark the message readed
	$pdo->exec($sql);
	$sql="select * from userlist where name='$people'";
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$mymessage=$res["mymessage"];
	$num=maxnum($mymessage,"id");
	$time=date('y-m-d H:i:s',time());
	$sql="insert into $mymessage values
		('$people','$time','$groupName','$groupID','$num','$type','0','$result')";          //tell the people whether he is in
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
	$name=$id."message";
	createMessage($name);              //create the table to save all the host send's message
	$name=$data["username"];				//insert into mygroups the new create group
	$sql="select * from userlist where name='$name'";
	$res=$pdo->query($sql);
	$res=$res->fetch();
	$mygroups=$res["mygroups"];
	$mymessage=$res["mymessage"];
	$sql="insert into $id values               
		('$owner','$mymessage','$mygroups')";           //insert the owner's message
	$pdo->exec($sql);
	$sql="insert into $mygroups values 
			('$groupName','$id','0')";
	$pdo->exec($sql);
	$array=array("message"=>"0");
	echo json_encode(array("data"=>$array));
}