<?php
include 'connection(urms).php';
header('Content-Type: application/json');

// Suppress warnings, but keep this for development only
error_reporting(E_ALL & ~E_WARNING);

$mysqli = new mysqli($servername, $username, $password, $db_name);

if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'error' => $mysqli->connect_error]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if ID is set in POST request
    if (!isset($_POST['ID'])) {
        echo json_encode(['success' => false, 'error' => 'No ID provided']);
        exit;
    }

    $id = $mysqli->real_escape_string($_POST['ID']);

    // Fields to update in the Student table
    $fields = ['Student_Name', 'pass', 'Student_email', 'Student_phone', 'Student_joining_date'];

    // Start building the SQL query
    $query = "UPDATE Student SET ";
    $params = [];
    $types = '';

    // Dynamically add fields to the query
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $query .= "$field = ?, ";
            $params[] = $mysqli->real_escape_string($_POST[$field]);
            $types .= 's'; 
        }
    }

    // Remove the last comma and space
    $query = rtrim($query, ", ");
    $query .= " WHERE ID = ?";
    $params[] = $id;
    $types .= 's'; 

    // Prepare the statement
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
        // The update was successful, redirect to the student table page
        header("Location: studenttablepage.php");
        exit(); // Don't forget to call exit after sending a header
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
        // Optionally, redirect to an error page or display an error message
    }

    $stmt->close();
}

$mysqli->close();
?>
