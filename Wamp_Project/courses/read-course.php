<?php require_once '../header.php'; ?>
<?php    
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Prepare a select statement
        $param_id = trim($_GET["id"]);
        $sql = "SELECT * FROM t_courses WHERE ID_course = '{$param_id}'";
        
        if($result = $db->query($sql)){
            if($result->num_rows == 1){
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = $result->fetch_array(MYSQLI_ASSOC);
                
                // Retrieve individual field value
                $ccode = $row["course_code"];
                $cdesc = $row["course_desc"];
                // URL doesn't contain valid id parameter. Redirect to error page
                
            }else{
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
                <label>Course Code</label>
                <p><b><?php echo $ccode; ?></b></p>
            </div>
            <div class="form-group">
                <label>Course Description</label>
                <p><b><?php echo $cdesc; ?></b></p>
            </div>

            
            <p><a href="../courses.php" class="btn btn-primary">Back</a></p>
        </div>
    </div>        
</div>

<?php require_once '../footer.php'; ?>