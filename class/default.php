<?php
/**
 * Code for the most sites for the beginning...
 */
require_once 'config.php';
require_once 'class/user.php';
require_once 'class/smarty/Smarty.class.php';

$template = new Smarty();
session_start();
$messages = array();
try
{
	$connection = new PDO($GLOBALS["db_type"].':dbname='.$GLOBALS["db_dbname"].';host='.$GLOBALS["db_host"].'', $GLOBALS["db_loginname"], $GLOBALS["db_loginpassword"]);
}
catch (PDOException $e)
{
	array_push($messages, $e->getMessage());
}
/**
 * @param unknown_type $url
 */
function checkNoNeedForLogin($url, $connection){
	foreach($connection->query('SELECT * FROM `config_loginneedlesssites` LIMIT 0 , 30') as $row){
		///echo $one['site'];
		//echo $row['site'];
		if(preg_match($row['site'], $url)){
			return true;
		}
	}
	return false;
}
//checkNoNeedForLogin(basename($_SERVER['REQUEST_URI']), $connection);
if((!isset($_SESSION["user"]))&&(basename($_SERVER['PHP_SELF'])!="login.php")){
	if(!checkNoNeedForLogin(basename($_SERVER['REQUEST_URI']), $connection)){
		header("Location: login.php");
	}
}
else{
	if((isset($_SESSION["user"]))&&(usertools::containRoles($GLOBALS["adminRoles"], $_SESSION["user"]->getRoles()))){
		$template->assign("admin", true);
	}
	$user = $_SESSION["user"];
}




?>