<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Resource</title>
    <link rel="stylesheet" href="profilescript.css"> <!-- Link to your CSS file -->
</head>
<body>

    <header>
        <h1>Update Resource</h1>
    </header>

    <main>
        <?php
            // Include your database connection file
            include 'connection(urms).php';
            try {
                // Your database connection code...
                $conn = new mysqli($servername, $username, $password, $db_name);
            
                // Check connection
                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }
            $recourseID = $_GET['recourseID'];

            // Fetch the existing resource data
            $stmt = $conn->prepare("SELECT * FROM recourse WHERE TypeId = ?");
            $stmt->bind_param("s", $recourseID);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $resource = $result->fetch_assoc();
                ?>

                <form action="processUpdateResource.php" method="post">
                    <input type="hidden" name="recourseID" value="<?php echo $recourseID; ?>">

                    <label for="courseID">Course ID:</label>
                    <input type="text" id="courseID" name="courseID" value="<?php echo $resource['Course_ID']; ?>" readonly>

                    <label for="resourceLink">Resource Link:</label>
                    <input type="text" id="resourceLink" name="resourceLink" value="<?php echo $resource['Link']; ?>" required>

                    <input type="submit" value="Update Resource">
                </form>

                <?php
            } else {
                echo "<p>Resource not found.</p>";
            }

            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    </main>

</body>
</html>
