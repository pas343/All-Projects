<?php
/* Database credentials. Assuming you are running MySQL
server with default setting (user 'root' with no password) */
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', '288WampProject');
 
/* Attempt to connect to MySQL database */
$db = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($db === false){
    die("ERROR: Could not connect. " . $db->connect_error);
}
?>