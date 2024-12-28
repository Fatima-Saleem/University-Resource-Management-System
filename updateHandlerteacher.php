<?php
include 'connection(urms).php';
header('Content-Type: application/json');

// Suppress warnings, but keep this for development only
error_reporting(E_ALL & ~E_WARNING);

if ($conn === false) {
    echo json_encode(['success' => false, 'error' => mysqli_connect_error()]);
    exit;
}

var_dump($_POST);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if ID is set in POST request
    if (!isset($_POST['ID'])) {
        echo json_encode(['success' => false, 'error' => 'No ID provided']);
        exit;
    }

    $id = mysqli_real_escape_string($conn, $_POST['ID']);
    
    // Set the table to update as Teacher
    $table = 'Teacher';
    $fields = ['Teacher_Name', 'pass', 'Teacher_email', 'Teacher_phone'];

    // Start building the SQL query
    $query = "UPDATE $table SET ";
    $params = [];
    $types = '';

    // Dynamically add fields to the query
    foreach ($fields as $field) {
        if (isset($_POST[$field])) {
            $query .= "$field = ?, ";
            $params[] = $conn->real_escape_string($_POST[$field]);
            $types .= 's'; 
        }
    }

    // Remove the last comma and space
    $query = rtrim($query, ", ");
    $query .= " WHERE ID = ?";
    $params[] = $id;
    $types .= 's'; 

    // Prepare the statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param($types, ...$params);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
        header("Location: teacherprofilepage.php");
        exit();
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}

$conn->close();
?>
