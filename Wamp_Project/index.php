<?php require_once 'header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="mt-5 mb-3 clearfix">
                <h2 class="pull-left">Search Records</h2>
            </div>
            <div class="mt-5 mb-3 clearfix">
                
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <select class="custom-select col-md-4" name="slt">
                        <option value="-Select-" selected>-Select-</option>
                        <option value="stid">Student ID</option>
                        <option value="ast">All Students</option>
                        <option value="acs">All Courses</option>
                    </select>
                    <select class="custom-select col-md-4" name="year">
                        <option value="-Year-" selected>-Year-</option>
                        <?php
                            for ($i=2010; $i < 2050 ; $i++) { 
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                        ?>
                    </select>
                    <select class="custom-select col-md-4" name="sems">
                        <option value="-Select-" selected>-Semester-</option>
                        <option value="FA">FA</option>
                        <option value="SP">SP</option>
                        <option value="S1">S1</option>
                        <option value="S2">S2</option>
                    </select>
                    <input type="Search" name="query" placeholder="Search using Select box parameters">
                    <input type="submit" value="Search">
                </form>
            </div>
            <?php
            
                

            if(isset($_POST["query"]) && !empty($_POST["query"]) && (trim($_POST["slt"]) == "stid")){
                $query = trim($_POST["query"]);
                $slct = trim($_POST["slt"]);
                $yr = trim($_POST["year"]);
                $sems = trim($_POST["sems"]);
                if ($slct == "stid" && $yr != "-Year-" && $sems != "-Select-") {
                    $sql = "SELECT * FROM t_schedules INNER JOIN t_students ON t_schedules.ID_student = t_students.ID_student INNER JOIN t_courses ON t_schedules.ID_course = t_courses.ID_course WHERE t_schedules.ID_student = '{$query}' AND t_schedules.sched_sem = '{$sems}' AND t_schedules.sched_yr = '{$yr}'";
                }elseif ($slct == "stid" && $yr == "-Year-" && $sems == "-Select-") {
                    $sql = "SELECT * FROM t_schedules INNER JOIN t_students ON t_schedules.ID_student = t_students.ID_student INNER JOIN t_courses ON t_schedules.ID_course = t_courses.ID_course WHERE t_schedules.ID_student = '{$query}'";
                }elseif ($slct == "stid" && $yr != "-Year-" && $sems == "-Select-") {
                    $sql = "SELECT * FROM t_schedules INNER JOIN t_students ON t_schedules.ID_student = t_students.ID_student INNER JOIN t_courses ON t_schedules.ID_course = t_courses.ID_course WHERE t_schedules.ID_student = '{$query}' AND t_schedules.sched_yr = '{$yr}'";
                }elseif ($slct == "stid" && $yr == "-Year-" && $sems != "-Select-") {
                    $sql = "SELECT * FROM t_schedules INNER JOIN t_students ON t_schedules.ID_student = t_students.ID_student INNER JOIN t_courses ON t_schedules.ID_course = t_courses.ID_course WHERE t_schedules.ID_student = '{$query}' AND t_schedules.sched_sem = '{$sems}'";
                }
                if($result = $db->query($sql)){
                    if($result->num_rows > 0){
                        echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                                echo "<tr>";
                                    echo "<th>Student ID</th>";
                                    echo "<th>First Name</th>";
                                    echo "<th>Last Name</th>";
                                    
                                    echo "<th>Semester</th>";
                                    echo "<th>Year</th>";
                                    echo "<th>Course Code</th>";
                                    echo "<th>Course Desc</th>";

                                echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while($row = $result->fetch_array()){
                                echo "<tr>";
                                    echo "<td>" . $row['ID_student'] . "</td>";
                                    echo "<td>" . $row['fname'] . "</td>";
                                    echo "<td>" . $row['lname'] . "</td>";
                                    echo "<td>" . $row['sched_sem'] . "</td>";
                                    echo "<td>" . $row['sched_yr'] . "</td>";
                                    echo "<td>" . $row['course_code'] . "</td>";
                                    echo "<td>" . $row['course_desc'] . "</td>";
                                    
                                echo "</tr>";
                            }
                            echo "</tbody>";                            
                        echo "</table>";
                        // Free result set
                        $result->free();
                    } else{
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }elseif (isset($_POST["query"]) && empty($_POST["query"]) && (trim($_POST["slt"]) == "ast")) {
                $yr = trim($_POST["year"]);
                $sems = trim($_POST["sems"]);

                $sql2 = "SELECT * FROM t_schedules INNER JOIN t_students ON t_schedules.ID_student = t_students.ID_student INNER JOIN t_courses ON t_schedules.ID_course = t_courses.ID_course WHERE t_schedules.sched_sem = '{$sems}' AND t_schedules.sched_yr = '{$yr}'";
                if($result = $db->query($sql2)){
                    if($result->num_rows > 0){
                        echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                                echo "<tr>";
                                    echo "<th>Student ID</th>";
                                    echo "<th>First Name</th>";
                                    echo "<th>Last Name</th>";
                                    
                                    echo "<th>Semester</th>";
                                    echo "<th>Year</th>";
                                    echo "<th>Course Code</th>";
                                    echo "<th>Course Desc</th>";

                                echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while($row = $result->fetch_array()){
                                echo "<tr>";
                                    echo "<td>" . $row['ID_student'] . "</td>";
                                    echo "<td>" . $row['fname'] . "</td>";
                                    echo "<td>" . $row['lname'] . "</td>";
                                    echo "<td>" . $row['sched_sem'] . "</td>";
                                    echo "<td>" . $row['sched_yr'] . "</td>";
                                    echo "<td>" . $row['course_code'] . "</td>";
                                    echo "<td>" . $row['course_desc'] . "</td>";
                                    
                                echo "</tr>";
                            }
                            echo "</tbody>";                            
                        echo "</table>";
                        // Free result set
                        $result->free();
                    } else{
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }elseif (isset($_POST["query"]) && empty($_POST["query"]) && (trim($_POST["slt"]) == "acs")) {
                $yr = trim($_POST["year"]);
                $sems = trim($_POST["sems"]);

                $sql1 = "SELECT * FROM t_schedules INNER JOIN t_students ON t_schedules.ID_student = t_students.ID_student INNER JOIN t_courses ON t_schedules.ID_course = t_courses.ID_course WHERE t_schedules.sched_sem = '{$sems}' AND t_schedules.sched_yr = '{$yr}'";
                if($result = $db->query($sql1)){
                    if($result->num_rows > 0){
                        echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                                echo "<tr>";
                                    echo "<th>Course ID</th>";
                                    echo "<th>Course Code</th>";
                                    echo "<th>Course Desc</th>";
                                    echo "<th>Year</th>";
                                    echo "<th>Semester</th>";
                                    

                                echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while($row = $result->fetch_array()){
                                echo "<tr>";
                                    echo "<td>" . $row['ID_course'] . "</td>";
                                    echo "<td>" . $row['course_code'] . "</td>";
                                    echo "<td>" . $row['course_desc'] . "</td>";
                                    echo "<td>" . $row['sched_yr'] . "</td>";
                                    echo "<td>" . $row['sched_sem'] . "</td>";
                                echo "</tr>";
                            }
                            echo "</tbody>";                            
                        echo "</table>";
                        // Free result set
                        $result->free();
                    } else{
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
            else {
                $sql = "SELECT * FROM t_schedules INNER JOIN t_students ON t_schedules.ID_student = t_students.ID_student INNER JOIN t_courses ON t_schedules.ID_course = t_courses.ID_course";
                if($result = $db->query($sql)){
                    if($result->num_rows > 0){
                        echo '<table class="table table-bordered table-striped">';
                            echo "<thead>";
                                echo "<tr>";
                                    echo "<th>Student ID</th>";
                                    echo "<th>First Name</th>";
                                    echo "<th>Last Name</th>";
                                    
                                    echo "<th>Semester</th>";
                                    echo "<th>Year</th>";
                                    echo "<th>Course Code</th>";
                                    echo "<th>Course Desc</th>";
                                    echo "<th>Start Date</th>";
                                    echo "<th>End Date</th>";

                                echo "</tr>";
                            echo "</thead>";
                            echo "<tbody>";
                            while($row = $result->fetch_array()){
                                echo "<tr>";
                                    echo "<td>" . $row['ID_student'] . "</td>";
                                    echo "<td>" . $row['fname'] . "</td>";
                                    echo "<td>" . $row['lname'] . "</td>";
                                    echo "<td>" . $row['sched_sem'] . "</td>";
                                    echo "<td>" . $row['sched_yr'] . "</td>";
                                    echo "<td>" . $row['course_code'] . "</td>";
                                    echo "<td>" . $row['course_desc'] . "</td>";
                                    echo "<td>" . $row['start_dte'] . "</td>";
                                    echo "<td>" . $row['end_dte'] . "</td>";
                                    
                                echo "</tr>";
                            }
                            echo "</tbody>";                            
                        echo "</table>";
                        // Free result set
                        $result->free();
                    } else{
                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                    }
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
            
            // Close connection
            $db->close();
            ?>
        </div>
    </div>        
</div>

<?php require_once 'footer.php'; ?>