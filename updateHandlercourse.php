<?php
include 'connection(urms).php';
header('Content-Type: application/json');

$mysqli = new mysqli($servername, $username, $password, $db_name);

if ($mysqli->connect_error) {
    echo json_encode(['success' => false, 'error' => $mysqli->connect_error]);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['ID'])) {
        echo json_encode(['success' => false, 'error' => 'No ID provided']);
        exit;
    }

    $id = $mysqli->real_escape_string($_POST['ID']);

    if (!isset($_POST['Course_Name']) || !isset($_POST['Semester'])) {
        echo json_encode(['success' => false, 'error' => 'Required fields not provided']);
        exit;
    }

    $courseName = $mysqli->real_escape_string($_POST['Course_Name']);
    $sem = $mysqli->real_escape_string($_POST['Semester']);

    $query = "UPDATE Course SET Course_Name = ?, Course_sem = ? WHERE Course_ID = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sis', $courseName, $sem, $id);

    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
        header("Location: coursetablepage.php");
        exit();
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }

    $stmt->close();
}

$mysqli->close();
?>
