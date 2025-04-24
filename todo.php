<?php

include('db.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $task = $_POST['task'];
    $description = $_POST['description'];
    $deadline = $_POST['deadline'];
    $trip_id = $_POST['trip_id'];
    $user_id = $_POST['user_id'];

   
    $query = "INSERT INTO todos (task, description, deadline, trip_id, user_id) VALUES ('$task', '$description', '$deadline', '$trip_id', '$user_id')";
    
   
    if ($conn->query($query)) {
        echo json_encode(['status' => 'success', 'id' => $conn->insert_id]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add task']);
    }
    exit();  
}


if (isset($_GET['delete'])) {
    $id = $_GET['delete']; 
    $conn->query("DELETE FROM todos WHERE id = $id");  
    exit();  
}


$result = $conn->query("SELECT * FROM todos ORDER BY id DESC");
$tasks = [];


while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;  
}


echo json_encode($tasks);
?>