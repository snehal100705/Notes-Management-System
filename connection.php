<?php
$host = "localhost";
$username = "root";
$password = '';
$dbname = "note_app";


$conn = mysqli_connect($host, $username, $password, $dbname);

if ($conn)
{
    echo "";
}
else{
    echo "DB not connected";
}
?>
