<?php require_once '../header.php'; ?>
<?php
 
// Define variables and initialize with empty values
$ccode = $cdesc = "";
$ccode_err = $cdesc_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_ccode = trim($_POST["ccode"]);
    if(empty($input_ccode)){
        $ccode_err = "Please enter course code.";
    } elseif(strlen($input_ccode) > 5){
        $ccode_err = "CourseCode must not be greater than 5 characters.";
    } else{
        $ccode = $input_ccode;
    }

    $input_cdesc = trim($_POST["cdesc"]);
    if(empty($input_cdesc)){
        $cdesc_err = "Please enter course description.";
    } elseif(strlen($input_cdesc) > 60){
        $cdesc_err = "Must not be greater than 60 characters.";
    } else{
        $cdesc = $input_cdesc;
    }
    
    // Check input errors before inserting in database
    if(empty($ccode_err) && empty($cdesc_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO t_courses (course_code, course_desc) VALUES (?, ?)";
 
        if($stmt = $db->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ss", $param_ccode, $param_cdesc);
            
            // Set parameters
            $param_ccode = $ccode;
            $param_cdesc = $cdesc;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: ../courses.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        $stmt->close();
    }
    
    // Close connection
    $db->close();
}
?>


<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mt-5">Create Course Record</h2>
            <p>Please fill this form and submit to add course record to the database.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Course Code</label>
                    <input type="text" name="ccode" class="form-control <?php echo (!empty($ccode_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $ccode; ?>">
                    <span class="invalid-feedback"><?php echo $ccode_err;?></span>
                </div>
                <div class="form-group">
                    <label>Course Description</label>
                    <input type="text" name="cdesc" class="form-control <?php echo (!empty($cdesc_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cdesc; ?>">
                    <span class="invalid-feedback"><?php echo $cdesc_err;?></span>
                </div>
                
                <input type="submit" class="btn btn-primary" value="Submit">
                <a href="../courses.php" class="btn btn-secondary ml-2">Cancel</a>
            </form>
        </div>
    </div>        
</div>

<?php require_once '../footer.php'; ?>