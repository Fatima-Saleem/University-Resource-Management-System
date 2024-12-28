<!DOCTYPE html>
<html lang="en">
<head>
    <title>Teacher Profile</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="teacherprofilescript.css"> 
</head>
<body>
    <header>
        <h1>Teacher Profile</h1>
    </header>

    <main>
            <?php
            include 'connection(urms).php'; 

            

            if(isset($_GET['userID'])) 
            {
                $teacherID = $conn->real_escape_string($_GET['userID']);
                
                if ($conn->connect_error) 
                {
                    die("Connection failed: " . $conn->connect_error);
                }

                $stmtTeacher = $conn->prepare("SELECT * FROM Teacher WHERE ID = ?");
                $stmtTeacher->bind_param("s", $teacherID);
                if (!$stmtTeacher->execute()) 
                {
                    echo "Error in teacher query: " . $stmtTeacher->error;
                }
                echo '<section class="teacher-info">';
                $stmtTeacher->execute();
                $resultTeacher = $stmtTeacher->get_result();

                if ($resultTeacher->num_rows > 0) 
                {
                    $teacher = $resultTeacher->fetch_assoc();

                    echo '<section class="teacher-info">';
                    echo '<h2>' . $teacher['Teacher_Name'] . '</h2>';
                    echo '<p><strong>ID:</strong> ' . $teacher['ID'] . '</p>';
                    echo '<p><strong>Email:</strong> ' . $teacher['Teacher_email'] . '</p>';
                    echo '<p><strong>Phone Number:</strong> ' . $teacher['Teacher_phone'] . '</p>';

                    echo '</section>';
                } 
                if (isset($stmtTeacher)) {
                    $stmtTeacher->close();
                }
            }
            else
            {
                echo '<p>No teacher found with the given ID.</p>';
            }
            ?>
        
            

            <?php
            $stmtRecourse = $conn->prepare("SELECT DISTINCT r.*, rt.TypeName, c.Course_Name, c.Course_ID FROM RecourseType rt INNER JOIN  Recourse r 
            ON rt.TypeId = r.TypeId INNER JOIN Course c ON r.Course_ID=c.Course_ID
            WHERE r.Teacher_ID = ? AND r.TypeId IN (SELECT TypeId FROM RecourseType)
            ");
            $stmtRecourse->bind_param("s", $teacherID);
            $stmtRecourse->execute();
            $resultRecourse = $stmtRecourse->get_result();

            if ($resultRecourse->num_rows > 0) {
                echo '<section class="box">';
                echo '<h2>My Resources</h2>';
                echo '<table>';
                echo '<thead>';
                echo '<tr>';
                echo '<th>Course ID</th>';
                echo '<th>Course Name</th>';
                echo '<th>Type ID</th>';
                echo '<th>Types</th>';
                echo '<th>Resource Link</th>'; 
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';
            
                while ($recourse = $resultRecourse->fetch_assoc()) {
                    echo '<tr>'; 
                    echo '<td>' . $recourse['Course_ID'] . '</td>';
                    echo '<td>' . $recourse['Course_Name'] . '</td>';
                    echo '<td>' . $recourse['TypeId'] . '</td>';
                    echo '<td>' . $recourse['TypeName'] . '</td>';
                    if ($recourse['Link']) {
                        echo '<td><a href="typeDetails.php?typeId=' . $recourse['TypeId'] . '&courseId=' . $recourse['Course_ID'] . '" target="_blank">View Resources</a></td>';
                    } 
                    
                }
            
                echo '</tbody>';
                echo '</table>';
                echo '</section>';
            } 
            else 
            {
                echo '<section class="box"><p>No resources found.</p></section>'; 
            }
            
            

            if (isset($stmtRecourse)) {
                $stmtRecourse->close();
            }
            // $stmtTeacher->close();
            $conn->close();
            
        ?>
        
    <script src="profilescript.js"></script>
</body>
</html>
