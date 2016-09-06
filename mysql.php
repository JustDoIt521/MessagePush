<?php
	function count($table)
	{	
		global $pdo;
		$sql="select Count(*) from '$table'";
		$res=$pdo->query($sql);
		$num=$res->fetchColumn();
		return $num;
	}
	function findGroups($username)
	{
		global $pdo;
		$sql="select * from userlist where name='$username'";
		$res=$pdo->query($sql);
		$res=$res->fetch();
		$groups=$res["groups"];
		return $groups;
	}
	function totalGroup($name)
	{
		global $pdo;
		$sql="create table '$name' 
			  (
			  	group varchar(1000) not null,
			  	id int not null
			  )ENGINE=InnoDB DEFAULT CHARSET=utf8";
		$res=$pdo->query($sql);
	}
	function createGroup($groups)
	{
		$sql="create table '$group'
			  (
			  		member varchar(100) not null
			  )" ;
		$pdo->query($sql);
	}