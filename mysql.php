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
				where varchar(100) not null,
				content text not null,
				condition int default 0
				)";
		$pdo->query($sql);
	}
 	function checkReturn()
 	{
 		global $pdo;
 		if($pdo->errorCode()=='00000')
 			return  0;
 		else
 			return 1;
 	}