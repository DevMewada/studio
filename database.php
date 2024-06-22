<?php 

$servername = "localhost";
$username = "root";
$password = "";
$database = "studio";

try{
    $connection = mysqli_connect($servername,$username,$password,$database);
}
catch(mysqli_sql_exception){
    echo "Could not connect to database";
}

if($connection) {
    echo "connected to the database";
}

?>