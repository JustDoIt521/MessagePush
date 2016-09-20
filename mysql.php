<?php
require_once("connect.php");
	function haveMessage($groupID,$time)
	{
		global $pdo;
		$message=$groupID."message";
		$sql="select * from $message where time>'$time'";
		$res=$pdo->query($sql);
		$res=$res->fetchAll();
		if($res==NUll)
		{
			return 0;
		}
		else
		{
			return 1;
		}
	}
	function loginTime($username)
	{
		global $pdo;
		$sql="select * from userlist where name='$username'";
		$res=$pdo->query($sql);
		$res=$res->fetch();
		$time=$res["lastLogin"];
		return $time;
	}
	function findnum($table)
	{	
		global $pdo;
		$sql="select count(*) from $table";
		$res=$pdo->query($sql);
		$res=$res->fetch();
		$num=$res[0];
		return $num;
	}
	function maxnum($table,$type)
	{
		global $pdo;
		$sql="select max($type) from $table";
		$res=$pdo->query($sql);
		$res=$res->fetch();
		if($res[0]==NULL)
		{
			$num=0;
		}
		else
		{
			$num=$res[0];
		}
		return $num+1;
	}
	function createMessage($name)    //for the groups create a message table
	{
		global $pdo;
		$sql="create table $name
				(
					content text not null,
					time varchar(100) not null
				)ENGINE=InnoDB DEFAULT CHARSET=utf8";
		$pdo->exec($sql);
	}
	function mygroups($name)     //create a table to save all my groups owner or join
	{
		global $pdo;
		$sql="create table $name
			(
				groupName varchar(1000) not null,
				groupID  varchar(1000) not null,
				type int not null
 			)ENGINE=InnoDB DEFAULT CHARSET=utf8";
 		$pdo->exec($sql);
	}
	function message($name)    //for user create a message table 
	{
		global $pdo;
		$sql="create table $name
			(
				people varchar(1000) not null,
				time varchar(1000),
				groupName varchar(1000),
				groupID varchar(1000),
				id int not null,
				type  varchar(1000) not null,
				reading int not null,
				result varchar(100)
			)ENGINE=InnoDB DEFAULT CHARSET=utf8";
		$pdo->exec($sql);
	}
 	function checkReturn()
 	{
 		global $pdo;
 		if($pdo->errorCode()=='00000')
 			return  0;
 		else
 			return 1;
 	}