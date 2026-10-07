<?php

header("Content-Type: application/json");

include '../config/conn.php';
//$action = $_POST['action'];


function register_system_action($conn){
    extract($_POST);
    $data = array();
// build the query to call the stored procedure 
   $query = "INSERT INTO system_actions(name,action,link_id)VALUES('$name','$system_action','$link_id')";

// Excecution

$result = $conn->query($query);

// chck if there is an error or not
if($result){
        $data = array("status" => true, "data" => "Registered 😊😊😎");
}else{
    $data = array("status" => false, "data" => $conn->error);
}
    echo json_encode($data); 
}

function update_system_action($conn){
    extract($_POST);
    $data = array();
// build the query to call the stored procedure 
   $query = "UPDATE system_actions set name='$name', action='$system_action', link_id='$link_id' WHERE id='$id'";

// Excecution

$result = $conn->query($query);

// chck if there is an error or not
if($result){
        $data = array("status" => true, "data" => "Updated successfully 😊😊😎");
}else{
    $data = array("status" => false, "data" => $conn->error);
}
    echo json_encode($data); 
}
function read_all_system_action($conn){
    $data = array();
    $array_data = array();
    $query = "SELECT * FROM `system_actions`";
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

function get_action_info($conn){
    extract($_POST);
    $data = array();
    $array_data = array();
    $query = "SELECT * FROM `system_actions` WHERE id = '$id'";
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
          $data = array("status" => true, "data" => $row);
    
       
    } else {
        $data = array("status" => false, "data" => $conn->error);
    }
    echo json_encode($data);
}

function delete_action_info($conn){
    extract($_POST);
    $data = array();
    $array_data = array();
    $query = "DELETE FROM `system_actions` WHERE id = '$id'";
    $result = $conn->query($query);
    if ($result) {
        
          $data = array("status" => true, "data" => "Deleted successfully👋👋👋");
    
       
    } else {
        $data = array("status" => false, "data" => $conn->error);
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