<?php

header("Content-Type: application/json");

include '../config/conn.php';
//$action = $_POST['action'];

function read_all_system_links(){
    $data = array();
    $data_array = array();
    $search_result = glob('../views/*.php');
    foreach($search_result as $sr){
$pure_link = explode('/',$sr);
$data_array[] = $pure_link[2];
    }
    if(count($search_result) > 0){
        $data = array("status" => true, "data" => $data_array);
    }else{
        $data = array("status" => false, "data" => "No links found");
    }
    echo json_encode($data);

//print_r($search_result);
}




function register_link($conn){
    extract($_POST);
    $data = array();
// build the query to call the stored procedure 
   $query = "INSERT INTO system_links (name,link,category_id) VALUES('$name','$link','$category')";

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
function update_link($conn){
    extract($_POST);
    $data = array();
// build the query to call the stored procedure 
    $query = "UPDATE system_links SET name='$name', link='$link', category_id='$category' WHERE id='$id'";

 
    // execute the query
    $result = $conn->query($query);

    // check if the query was successful
    if ($result) { 
        $data = array("status" => true, "data" => "updated successfully");
    } else {
        $data = array("status" => false, "data" => $conn->error);
    }
    echo json_encode($data); 
}
function read_all_db_links($conn){
    $data = array();
    $array_data = array();
    $query = "SELECT * FROM `system_links`";
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

function get_link_info($conn){
    extract($_POST);
    $data = array();
    $array_data = array();
    $query = "SELECT * FROM `system_links` WHERE id = '$id'";
    $result = $conn->query($query);
    if ($result) {
        $row = $result->fetch_assoc();
          $data = array("status" => true, "data" => $row);
    
       
    } else {
        $data = array("status" => false, "data" => $conn->error);
    }
    echo json_encode($data);
}

function delete_link_info($conn){
    extract($_POST);
    $data = array();
    $array_data = array();
    $query = "DELETE FROM `system_links` WHERE id = '$id'";
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