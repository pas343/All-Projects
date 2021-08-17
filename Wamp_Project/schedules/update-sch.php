<?php require_once '../header.php'; ?>
<?php
 
    // Define variables and initialize with empty values
    $studId = $courseId = $schedyr = $schedsem = $grade = "";
    $IDstudent_err = $IDcourse_err = $schedyr_err = $schedsem_err = $grade_err = "";

    function isIdValid($id){
        global $db;
        $queryd = "SELECT * FROM t_students WHERE ID_student = '{$id}'";

        $results = mysqli_query($db,$queryd);
        if (mysqli_num_rows($results) > 1) {
            return true;
        } else {
            return false;
        }
    }

    function isCourseIdValid($id){
        global $db;
        $queryd = "SELECT * FROM t_courses WHERE ID_course = '{$id}'";

        $results = mysqli_query($db,$queryd);
        if (mysqli_num_rows($results) > 0) {
            return true;
        } else {
            return false;
        }
    }

    function gradeLetter(float $value): string {
        if ($value >= 0.0 && $value < 1.0) {
            return "F";
        }elseif ($value >= 1.0 && $value < 2.0) {
            return "D";
        }elseif ($value >= 2.0 && $value < 2.5) {
            return "C";
        }elseif ($value >= 2.5 && $value < 3.0) {
            return "C+";
        }elseif ($value >= 3.0 && $value < 3.5) {
            return "B";
        }elseif ($value >= 3.5 && $value < 3.75) {
            return "B+";
        }elseif ($value >= 3.75 && $value < 4.0) {
            return "A";
        }else {
            return "A+";
        }
    }
     
    // Processing form data when form is submitted
    if(isset($_POST["id"]) && !empty($_POST["id"])){
        
        echo '<script>alert("Hello World");</script>';$id = $_POST["id"];
        // Validate name
        $input_stud = trim($_POST["studId"]);
        if(empty($input_stud)){
            $IDstudent_err = "Please enter a Student Id.";
        } elseif (isIdValid($input_stud)) {
            $IDstudent_err = "Please enter valid Student Id. Not registered";
        }else{
            $studId = $input_stud;
        }

        $input_course = trim($_POST["courseId"]);
        if(empty($input_course)){
            $IDcourse_err = "Please enter a Course Id.";
        } elseif (isCourseIdValid($input_stud)) {
            $IDcourse_err = "Please enter valid Course Id. Not registered";
        }else{
            $courseId = $input_course;
        }

        $input_schedyr = trim($_POST["schedyr"]);
        if($input_schedyr == "-Select-"){
            $schedyr_err = "Please select a year.";
        } else{
            $schedyr = $input_schedyr;
        }

        $input_schedsem = trim($_POST["schedsem"]);
        if($input_schedsem == "-Select-"){
            $schedsem_err = "Please select a semester.";
        } else{
            $schedsem = $input_schedsem;
        }

        $input_grade = trim($_POST["grade"]);
        if($input_grade == "-Select-"){
            $grade_err = "Please select a grade.";
        }else{
            $grade = $input_grade;
            // $grade = gradeLetter($grade);
        }



        
        
        // Check input errors before inserting in database
        if(empty($IDstudent_err) && empty($IDcourse_err) && empty($schedyr_err) && empty($schedsem_err) && empty($grade_err)){
            // Prepare an insert statement
            $sql1 = "UPDATE t_schedules SET ID_course=?, ID_student=?, sched_yr=?, sched_sem=?, grade_letter=? WHERE ID_schedule =?";
     echo '<script>alert("Hello World");</script>';
            if($stmt = $db->prepare($sql1)){
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("sssssi", $param_courseId, $param_studId, $param_schyr, $param_schsem, $param_grade,$param_gid);
                
                // Set parameters
                $param_courseId = $courseId;
                $param_studId = $studId;
                $param_schyr = $schedyr;
                $param_schsem = $schedsem;
                $param_grade = $grade;
                $param_gid = $id;
                
                // Attempt to execute the prepared statement
                if($stmt->execute()){
                    // Records updated successfully. Redirect to landing page
                    header("location: ../schedules.php");
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
    } else {
        if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
            // Prepare a select statement
            $id = trim($_GET["id"]);
            $sql = "SELECT * FROM t_schedules WHERE ID_schedule = '{$id}'";
            
            if($result = $db->query($sql)){
                if($result->num_rows == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $studId = $row["ID_student"];
                    $courseId = $row["ID_course"];
                    $schedyr = $row["sched_yr"];
                    $schedsem = $row["sched_sem"];
                    $grade = $row["grade_letter"];
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
    }
?>


<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mt-5">Update Schedule Record</h2>
            <p>Please edit the input values and submit to update schedule record.</p>
            <form action="<?php echo htmlspecialchars(basename($_SERVER["REQUEST_URI"])); ?>" method="post">
                <div class="form-group">
                    <label>Student ID:</label>
                    <input type="text" name="studId" class="form-control <?php echo (!empty($IDstudent_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $studId; ?>">
                    <span class="invalid-feedback"><?php echo $IDstudent_err;?></span>
                </div>
                <div class="form-group">
                    <label>Course ID:</label>
                    <input type="text" name="courseId" class="form-control <?php echo (!empty($IDcourse_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $courseId; ?>">
                    <span class="invalid-feedback"><?php echo $IDcourse_err;?></span>
                </div>
                <div class="form-group">
                    <label>Schedule Year</label>
                    <select name="schedyr" class="custom-select <?php echo (!empty($schedyr_err)) ? 'is-invalid' : ''; ?>">
                        <option value="-Select-">-Select-</option>
                        <?php
                            for ($i=2010; $i < 2050 ; $i++) { 
                                if ($i == $schedyr) {
                                   echo '<option value="'.$i.'" selected>'.$i.'</option>';
                                }else {
                                    echo '<option value="'.$i.'">'.$i.'</option>';
                                }
                            }
                        ?>
                    </select>
                    <span class="invalid-feedback"><?php echo $schedyr_err;?></span>
                </div>
                <div class="form-group">
                    <label>Schedule Sem</label>
                    <select name="schedsem" class="custom-select <?php echo (!empty($schedsem_err)) ? 'is-invalid' : ''; ?>">
                        <option value="-Select-">-Select-</option>
                        <option value="FA" <?php if($schedsem == "FA") echo "selected" ?>>FA</option>
                        <option value="SP" <?php if($schedsem == "SP") echo "selected" ?>>SP</option>
                        <option value="S1" <?php if($schedsem == "S1") echo "selected" ?>>S1</option>
                        <option value="S2" <?php if($schedsem == "S2") echo "selected" ?>>S2</option>
                    </select>
                    <span class="invalid-feedback"><?php echo $schedsem_err;?></span>
                </div>
                <div class="form-group">
                    <label>Grade Point</label>
                    <select name="grade" class="custom-select <?php echo (!empty($grade_err)) ? 'is-invalid' : ''; ?>">
                        <option value="-Select-" selected>-Select-</option>
                        <option value="A+" <?php if($grade == "A+") echo "selected" ?>>A+</option>
                        <option value="A" <?php if($grade == "A") echo "selected" ?>>A</option>
                        <option value="B+" <?php if($grade == "B+") echo "selected" ?>>B+</option>
                        <option value="B" <?php if($grade == "B") echo "selected" ?>>B</option>
                        <option value="C+" <?php if($grade == "C+") echo "selected" ?>>C+</option>
                        <option value="C" <?php if($grade == "C") echo "selected" ?>>C</option>
                        <option value="D" <?php if($grade == "D") echo "selected" ?>>D</option>
                        <option value="F" <?php if($grade == "F") echo "selected" ?>>F</option>
                    </select>
                    <span class="invalid-feedback"><?php echo $grade_err;?></span>
                </div>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="submit" class="btn btn-primary" value="Update">
                <a href="../schedules.php" class="btn btn-secondary ml-2">Cancel</a>
            </form>
        </div>
    </div>        
</div>

<?php require_once '../footer.php'; ?>