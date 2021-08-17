<?php require_once '../header.php'; ?>

<?php
// Check existence of id parameter before processing further
	if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
	    // Prepare a select statement
	    $param_id = trim($_GET["id"]);
	    $sql = "SELECT * FROM t_students WHERE ID_student = '{$param_id}'";
	    
	    if($result = $db->query($sql)){
            if($result->num_rows == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = $result->fetch_array();
                
                // Retrieve individual field value
                $fname = $row["fname"];
                $lname = $row["lname"];
                $phone = $row["phone"];
                $email = $row["email"];
                $status = $row["status"];
                $startd = $row["start_dte"];
                $endd = $row["end_dte"];
                $sdate = date_create($startd);
                $startd = date_format($sdate, "Y/m/d");
                $edate = date_create($endd);
                $endd = date_format($edate, "Y/m/d");
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
                <label>First Name</label>
                <p><b><?php echo $row["fname"]; ?></b></p>
            </div>
            <div class="form-group">
                <label>Last Name</label>
                <p><b><?php echo $lname; ?></b></p>
            </div>
            <div class="form-group">
                <label>Phone</label>
                <p><b><?php echo $phone; ?></b></p>
            </div>
            <div class="form-group">
                <label>Email</label>
                <p><b><?php echo $email; ?></b></p>
            </div>
            <div class="form-group">
                <label>Status</label>
                <p><b><?php echo $status; ?></b></p>
            </div>
            <div class="form-group">
                <label>Start Date</label>
                <p><b><?php echo $startd; ?></b></p>
            </div>
            <div class="form-group">
                <label>End Date</label>
                <p><b><?php echo $endd; ?></b></p>
            </div>
            <p><a href="../students.php" class="btn btn-primary">Back</a></p>
        </div>
    </div>        
</div>

<?php require_once '../footer.php'; ?>