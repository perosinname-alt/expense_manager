<?php
session_start();
header("Content-Type: application/json");

include '../config/conn.php';

function login($conn){
 extract($_POST);
    $data = array();
    $array_data = array();
    $query ="CALL login_sp('$username','$password')";
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
        if(isset($row['Msg'])){
            if($row['Msg']== "Deny"){
                $data = array("status" => "false", "data" => "Usename OR  Password is Incorrect");
                
            }else {
                $data = array("status" => "false", "data" => "Usename Locked By admin");

               
            }
           
        }else {
            foreach($row as $key=> $value ){
                $_SESSION[$key] = $value;
            }
             $data = array("status" => "true", "data" => "success");
        }
           
        }else{
                $data = array("status" => "false", "data" => $conn->error);
    }
     echo json_encode($data);
        }
       

        
   




if(isset($_POST['action'])){
    $action = $_POST['action'];
    $action($conn);
}else{
    echo json_encode(array("status" => false, "data" => " action required......"));
}

?>