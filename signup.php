<?php
include('db.php'); // Include DB connection

// Check if the form is submitted via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Sanitize and validate inputs
    $username = htmlspecialchars(trim($_POST['username']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL); // Sanitize email input
    $password = $_POST['password'];
    
    // Validate inputs
    if (empty($username) || empty($email) || empty($password)) {
        echo "All fields are required.";
        exit();
    }

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit();
    }

    // Check if the email already exists in the database
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    
    if ($stmt->num_rows > 0) {
        echo "Email already exists. Please use a different email.";
        exit();
    }

    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the new user
    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $email, $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
        echo "Registration successful. You can now log in.";
    } else {
        echo "Error: " . $stmt->error; // Display error if insertion fails
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
