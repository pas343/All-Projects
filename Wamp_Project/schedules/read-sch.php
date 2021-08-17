<?php require_once '../header.php'; ?>

<?php
// Check existence of id parameter before processing further
	if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
	    // Prepare a select statement
	    $param_id = trim($_GET["id"]);
	    $sql = "SELECT * FROM t_schedules WHERE ID_schedule = '{$param_id}'";
	    
	    if($result = $db->query($sql)){
            if($result->num_rows == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = $result->fetch_array();
                
                // Retrieve individual field value
                $studId = $row["ID_student"];
                $courseId = $row["ID_course"];
                $schedyr = $row["sched_yr"];
                $schedsem = $row["sched_sem"];
                $grade = $row["grade_letter"];
                $queryd = "SELECT * FROM t_students WHERE ID_student = '{$studId}'";

                $results = mysqli_query($db,$queryd);
                if (mysqli_num_rows($results) > 0) {
                    $r = mysqli_fetch_assoc($results);
                    $fullname = $r["fname"]." ".$r["lname"];
                }
            } else{
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: ../error.php");
                exit();
            }
	            
	       
	    } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
	     
	    // Close statement
	    // $stmt->close();
	    
	    // Close connection
	    $db->close();
	} else{
	    // URL doesn't contain id parameter. Redirect to error page
	    header("location: ../error.php");
	    exit();
	}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mt-5 mb-3">View Record</h1>
            <div class="form-group">
                <label>Student ID</label>
                <p><b><?php echo $studId; ?></b></p>
            </div>
            <div class="form-group">
                <label>Student Name</label>
                <p><b><?php echo $fullname; ?></b></p>
            </div>
            <div class="form-group">
                <label>Course ID</label>
                <p><b><?php echo $courseId; ?></b></p>
            </div>
            <div class="form-group">
                <label>Schedule Year</label>
                <p><b><?php echo $schedyr; ?></b></p>
            </div>
            <div class="form-group">
                <label>Schedule Sem</label>
                <p><b><?php echo $schedsem; ?></b></p>
            </div>
            <div class="form-group">
                <label>Grade</label>
                <p><b><?php echo $grade; ?></b></p>
            </div>
            
            <p><a href="../schedules.php" class="btn btn-primary">Back</a></p>
        </div>
    </div>        
</div>

<?php require_once '../footer.php'; ?>