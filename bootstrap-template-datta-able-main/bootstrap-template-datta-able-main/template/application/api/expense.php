<?php

header("Content-Type: application/json");

include '../config/conn.php';
//$action = $_POST['action'];

function register_expense($conn){
    extract($_POST);
    $data = array();
// build the query to call the stored procedure 
    $query = "CALL register_expense_sp('','$amount','$type','$description','USR001')";

 
    // execute the query
    $result = $conn->query($query);

    // check if the query was successful
    if ($result) { 
        $row = $result->fetch_assoc();
        if($row['message'] == 'Deny'){
            $data = array("status" => false, "data" => "insufficient balance 😊😊😎");
        } elseif($row['message'] == 'Registered'){
            $data =array( "status" => true, "data" => "registered successfully");
        }

        
    } else {
        $data = array("status" => false, "data" => $conn->error);
    }
    echo json_encode($data); 
}
function update_expense($conn){
    extract($_POST);
    $data = array();
// build the query to call the stored procedure 
    $query = "CALL register_expense_sp('$id','$amount','$type','$description','USR001')";

 
    // execute the query
    $result = $conn->query($query);

    // check if the query was successful
    if ($result) { 
        $row = $result->fetch_assoc();
      
        if($row['message'] == 'Updated'){
            $data =array( "status" => true, "data" => "updated successfully");
        }
    
        
    } else {
        $data = array("status" => false, "data" => $conn->error);
    }
    echo json_encode($data); 
}
function get_user_transactions($conn){
    $data = array();
    $array_data = array();
    $query = "SELECT `id`, `amount`, `type`, `description` FROM `expense` WHERE 1";
    $result = $conn->query($query);
    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $array_data[] = $row;
        }
        $data = array("status" => "true", "data" => $array_data);
    } else {
        $data = array("status" => "false", "data" => $conn->error);
    }
    echo json_encode($data);
}
function get_expense_info($conn){
    extract($_POST);
    $data = array();
    $array_data = array();
    $query = "SELECT * FROM `expense` WHERE id = '$id'";
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
          $data = array("status" => "true", "data" => $row);
    
       
    } else {
        $data = array("status" => "false", "data" => $conn->error);
    }
    echo json_encode($data);
}
if(isset($_POST['action'])){
$action = $_POST['action'];
$action($conn);
}else{
    echo json_encode(array("status" => false, "data" => " action required......") );
}

?>