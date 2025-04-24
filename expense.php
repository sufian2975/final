<?php
header('Content-Type: application/json');
include('db.php');

// Insert expense into database when POST request is made
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ;
    $amount = $_POST['amount'] ;
    $category = $_POST['category'] ;
    $date = $_POST['date'] ;

    // Insert data into the database
    $query = "INSERT INTO expenses (category, amount, date, description) VALUES ('$category', '$amount', '$date', '$name')";
    if ($conn->query($query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error']);
    }
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete']; 
    $conn->query("DELETE FROM expenses WHERE id = $id");  
    exit();  
}

// Fetch all expenses when GET request is made
$result = $conn->query("SELECT * FROM expenses ORDER BY id DESC");
$expenses = [];
while ($row = $result->fetch_assoc()) {
    $expenses[] = $row;
}

echo json_encode($expenses);
?>