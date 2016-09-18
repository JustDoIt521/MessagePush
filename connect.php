<?php
	$dbms="mysql";
	$host="localhost";
	$dbName="MessagePush";
	$user='root';
	$password="root";
	$dsn="$dbms:host=$host;dbname=$dbName";
	try
	{
		$pdo=new pdo($dsn,$user,$password,array(PDO::MYSQL_ATTR_INIT_COMMAND=>"set names utf8"));
	}catch(PDOException $e)
	{
		die("connect error !".$e->getMessage()."<br>");
	}