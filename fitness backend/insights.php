<?php

// Connect to SQLite database
$db = new SQLite3('fitness.db');

// Fetch workout data from database
$result = $db->query('SELECT exercise_type, SUM(calories_burned) AS total_calories FROM workouts GROUP BY exercise_type');

// Create an array to store insights
$insights = [];

// Populate insights array with workout data
while ($row = $result->fetchArray()) {
    $insights[$row['exercise_type']] = $row['total_calories'];
}

// Close database connection
$db->close();

// Extract data for Chart.js
$exerciseTypes = array_keys($insights);
$totalCalories = array_values($insights);

// Generate Chart.js script
echo '<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>';
echo '<canvas id="myChart"></canvas>';
echo '<script>';
echo 'var ctx = document.getElementById("myChart").getContext("2d");';
echo 'var myChart = new Chart(ctx, {';
echo 'type: "bar",';
echo 'data: {';
echo 'labels: ' . json_encode($exerciseTypes) . ',';
echo 'datasets: [{';
echo 'label: "Total Calories Burned",';
echo 'data: ' . json_encode($totalCalories) . ',';
echo 'backgroundColor: "rgba(75, 192, 192, 0.2)",';
echo 'borderColor: "rgba(75, 192, 192, 1)",';
echo 'borderWidth: 1';
echo '}]';
echo '},';
echo 'options: {';
echo 'scales: {';
echo 'y: {';
echo 'beginAtZero: true';
echo '}';
echo '}';
echo '}';
echo ');';
echo '</script>';

?>
