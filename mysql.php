<?php
	function count($table)
	{	
		global $pdo;
		$sql="select Count(*) from '$table'";
		$res=$pdo->query($sql);
		$num=$res->fetchColumn();
		return $num;
	}
	function createMessage($name)
	{
		global $pdo;
		$sql="create table '$name'
				(
					content text not null,
					time varchara(100) not null
				)";
		$pdo->exec($sql);
	}
	function mygroups($name)
	{
		global $pdo;
		$sql="create table '$name'
			(
				groupName varchar(1000) not null,
				groupID  varchar(1000) not null,
				host varchar(50) not null
 			)"
 		$pdo->exec($sql);
	}
	function addmembers($name)
	{
		global $pdo;
		$sql="create table '$name'
			(
				people varchar(1000) not null
				groupName varchar(1000)  not null,
				groupID varchar(1000) not null
			)"
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