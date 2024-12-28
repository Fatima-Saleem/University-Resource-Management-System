<?php
include 'connection(urms).php'; 
$mysqli = new mysqli($servername, $username, $password, $db_name);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

$id = '';
$teacherName = '';
$pass = '';
$teacherEmail = '';
$teacherPhone = '';

if (isset($_GET['ID'])) {
    $id = $mysqli->real_escape_string($_GET['ID']);
    $query = "SELECT * FROM Teacher WHERE ID = '$id'";
    $result = $mysqli->query($query);

    if ($row = $result->fetch_assoc()) {
        $teacherName = $row['Teacher_Name'];
        $pass = $row['pass'];
        $teacherEmail = $row['Teacher_email'];
        $teacherPhone = $row['Teacher_phone'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Table</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="styletable.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

</head>
<body>
    <form action="updateHandlerteacher.php" method="post">
        <input type="hidden" name="ID" value="<?php echo $id; ?>">
        <input type="text" name="Teacher_Name" value="<?php echo $teacherName; ?>">
        <input type="text" name="pass" value="<?php echo $pass; ?>">
        <input type="text" name="Teacher_email" value="<?php echo $teacherEmail; ?>">
        <input type="text" name="Teacher_phone" value="<?php echo $teacherPhone; ?>">
       
       <input type="submit" value="Update">
    </form>
</body>
</html>