<?php

header("Content-Type: application/json");

include '../config/conn.php';

function register_user($conn){
    extract($_POST);

    $data = array();
    $error_array = array();
    $new_id = generate($conn);

    // hubi in sawir la soo diray
    if(!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK){
        echo json_encode(array("status" => false, "data" => array("Please choose an image")));
        return;
    }

    $file_name = $_FILES['image']['name'];
    $file_type = $_FILES['image']['type'];
    $file_size = $_FILES['image']['size'];
    $save_name = $new_id .".png";

    // allowed images
    $allowedImages = ["image/jpg","image/jpeg","image/png"];
    $max_size = 5 * 1024 * 1024;

    if(in_array($file_type,$allowedImages)){
        if($file_size > $max_size){
            $error_array[] = $file_size/1024/1024 . " MB Files size must be less then" . $max_size/1024/1024  .  "MB";
        }
    }else{
        $error_array[] = "This file is not Allowed" . $file_type;
    }

    // build the query to call the stored procedure
    if(count($error_array) <= 0){
        $query = "INSERT INTO `users`(`id`, `username`, `password`, `image`) VALUES('$new_id','$username',MD5('$password'), '$save_name')";

        // execute the query
        $result = $conn->query($query);

        // check if the query was successful
        if ($result) {
            move_uploaded_file($_FILES['image']['tmp_name'], "../uploads/".$save_name);
            $data = array("status" => true, "data" => "SucessFully Registered");
        } else {
            $data = array("status" => false, "data" => $conn->error);
        }
    }else{
        $data = array("status" => false, "data" => $error_array);
    }

    echo json_encode($data);
}
function update_expense($conn){
    extract($_POST);
    $data = array();
    $query = "CALL register_expense_sp('$id','$amount','$type','$description','USR001')";

    $result = $conn->query($query);

    if ($result) {
        $row = $result->fetch_assoc();
        if($row['message'] == 'Updated'){
            $data = array("status" => true, "data" => "updated successfully");
        }
    } else {
        $data = array("status" => false, "data" => $conn->error);
    }
    echo json_encode($data);
}
function get_user_list($conn){
    $data = array();
    $array_data = array();
    $query = "SELECT*FROM `users` ";
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

function delete_expense_info($conn){
    extract($_POST);
    $data = array();
    $query = "DELETE FROM `expense` WHERE id = '$id'";
    $result = $conn->query($query);
    if ($result) {
        $data = array("status" => "true", "data" => "Deleted successfully👋👋👋");
    } else {
        $data = array("status" => "false", "data" => $conn->error);
    }
    echo json_encode($data);
}
function generate($conn){
    $new_id = ' ';
    $data = array();
    $query = "SELECT * FROM `users` order by users.id DESC limit 1";
    $result = $conn->query($query);
    if ($result) {
        $num_rows = $result->num_rows;
        if($num_rows > 0){
            $row = $result->fetch_assoc();
            $new_id = ++$row['id'];
        }else{
            $new_id = "USR001";
        }
    } else {
        $data = array("status" => "false", "data" => $conn->error);
    }
    return $new_id;
}
if(isset($_POST['action'])){
    $action = $_POST['action'];
    $action($conn);
}else{
    echo json_encode(array("status" => false, "data" => " action required......"));
}

?>