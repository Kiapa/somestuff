<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connect to SQLite database
    $db = new SQLite3('fitness.db');

    // Get form data
    $username = $_POST['username'];
    $exerciseType = $_POST['exercise_type'];
    $reps = $_POST['reps'];
    $sets = $_POST['sets'];
    $time = $_POST['time'];

    // Fetch user ID and user details from database
    $stmt = $db->prepare('SELECT id, height, weight, age FROM users WHERE username = :username');
    $stmt->bindValue(':username', $username, SQLITE3_TEXT);
    $result = $stmt->execute()->fetchArray();
    $userId = $result['id'];
    $height = $result['height'];
    $weight = $result['weight'];
    $age = $result['age'];

    // Calculate calories burned
    $bmr = 10 * $weight + 6.25 * $height - 5 * $age + 5;
    $activityFactors = [
        'sit-ups' => 3.5,
        'push-ups' => 3.5,
        'squats' => 4.0,
        'plank' => 3.5
    ];
    $met = $activityFactors[$exerciseType];
    $energyPerExercise = $met * $time * $reps * $sets / 60;
    $caloriesBurned = $bmr * $energyPerExercise / 60;

    // Insert workout and calories burned into database
    $stmt = $db->prepare('INSERT INTO workouts (user_id, exercise_type, reps, sets, time, calories_burned) VALUES (:user_id, :exercise_type, :reps, :sets, :time, :calories_burned)');
    $stmt->bindValue(':user_id', $userId, SQLITE3_INTEGER);
    $stmt->bindValue(':exercise_type', $exerciseType, SQLITE3_TEXT);
    $stmt->bindValue(':reps', $reps, SQLITE3_INTEGER);
    $stmt->bindValue(':sets', $sets, SQLITE3_INTEGER);
    $stmt->bindValue(':time', $time, SQLITE3_FLOAT);
    $stmt->bindValue(':calories_burned', $caloriesBurned, SQLITE3_FLOAT);
    $stmt->execute();

    // Close database connection
    $db->close();

    echo 'Workout recorded successfully!';
}

?>
