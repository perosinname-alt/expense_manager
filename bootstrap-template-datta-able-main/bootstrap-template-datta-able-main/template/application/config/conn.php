<?php
$conn =  new mysqli("localhost", "root", "", "expense");

if ($conn->connect_error) {
    echo $conn->error;
}


 



?>
