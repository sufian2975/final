<?php
session_start(); // Start the session to access session data

include('db.php'); // Include DB connection

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'You need to log in first']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input data
    $name = htmlspecialchars(trim($_POST['name']));
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $duration = $_POST['duration'];
    $people = $_POST['people'];

    // Basic validation
    if (empty($name) || empty($start) || empty($end) || empty($duration) || empty($people)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
        exit();
    }

    // Prepare SQL query using a prepared statement to avoid SQL injection
    $stmt = $conn->prepare("INSERT INTO trips (name, start_date, end_date, duration, people, user_id) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssiii", $name, $start, $end, $duration, $people, $_SESSION['user_id']); // Bind user_id from session

    // Execute and handle the response
    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Trip added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add trip: ' . $stmt->error]);
    }

    $stmt->close();
    $conn->close();
}
?>
