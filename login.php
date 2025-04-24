<?php
session_start(); // Start the session

// Include the database connection
include('db.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get values from POST
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // "s" = string type
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // If credentials are correct, set session variables
            $_SESSION['user_id'] = $user['id']; // Store user ID in session
            $_SESSION['email'] = $user['email']; // Store email in session

            // Redirect user to the homepage or any protected page
            header('Location: index.php'); // Change this to the page you want to redirect to
            exit(); // Stop further code execution
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No user found with this email.";
    }

    // Close the statement and database connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Wanderlust</title>
</head>
<body>

    <h2>Login</h2>

    <!-- Show error message if any -->
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <!-- Login Form -->
    <form method="POST" action="login.php">
        <label for="email">Email: </label>
        <input type="email" name="email" required><br><br>
        
        <label for="password">Password: </label>
        <input type="password" name="password" required><br><br>
        
        <button type="submit">Login</button>
    </form>

</body>
</html>
