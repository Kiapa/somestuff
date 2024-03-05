<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to SQLite database
    $db = new SQLite3('fitness.db');

    // Get form data
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Fetch user from database
    $stmt = $db->prepare('SELECT id, password FROM users WHERE username = :username');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute()->fetchArray();

    if ($result && password_verify($password, $result['password'])) {
        echo 'Login successful!';
    } else {
        echo 'Invalid username or password!';
    }

    // Close database connection
    $db->close();
}

?>
