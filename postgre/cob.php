<?php

try{

	$myPDO =new PDO ("pgsql:host=;dbname=00f_unsadasentimen_analis","postgres","pgadmin");
	
	$sql_query1 ="insert into latihan(id,nama,telepon,email)values(1,'gita','038302','gta@gmail.com')";
	$myPDO->query($sql_query1);
	
	$sql = "select * from latihan";
	
	forech($myPDO->query($sql)as $row)
	{
		print"<br/>";
		print $row[id].'-'.$row[nama].'-'.$row[telepon].'-'.$row[email].' "<br/>";
	
	}
		}catch(PDOException $e){
		echo $e->getmessage();
	}
?>