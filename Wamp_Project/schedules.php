<?php require_once 'header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="mt-5 mb-3 clearfix">
                <h2 class="pull-left">Schedules Details</h2>
                <a href="schedules/new-sch.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Schedule</a>
            </div>
            <div class="mt-5 mb-3 clearfix">

                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <select class="custom-select col-md-4" name="slt">
                        <option value="-Select-" selected>-Select-</option>
                        <option value="scid">Schedule ID</option>
                        <option value="stid">Student ID</option>
                        <option value="cid">Course ID</option>
                        <option value="yr">Year</option>
                        <option value="sem">Semester</option>
                        <option value="grade">Grade</option>
                    </select>
                    <input type="Search" name="query" placeholder="Search using Select box parameters">
                    <input type="submit" value="Search">
                </form>
            </div>
            <?php
            
            // Attempt select query execution
            if(isset($_POST["query"]) && !empty($_POST["query"])){
                $query = trim($_POST["query"]);
                $slct = trim($_POST["slt"]);
                if ($slct == "scid") {
                    $sql = "SELECT * FROM t_schedules WHERE ID_schedule = '{$query}'";
                }elseif ($slct == "stid") {
                    $sql = "SELECT * FROM t_schedules WHERE ID_student = '{$query}'";
                }elseif ($slct == "cid") {
                    $sql = "SELECT * FROM t_schedules WHERE ID_course = '{$query}'";
                }elseif ($slct == "yr") {
                    $sql = "SELECT * FROM t_schedules WHERE sched_yr = '{$query}'";
                }elseif ($slct == "sem") {
                    $sql = "SELECT * FROM t_schedules WHERE sched_sem = '{$query}'";
                }elseif ($slct == "grade") {
                    $sql = "SELECT * FROM t_schedules WHERE grade_letter = '{$query}'";
                }else{
                    $sql = "SELECT * FROM t_schedules";
                }
                
            }else {
                $sql = "SELECT * FROM t_schedules";
            }
            if($result = $db->query($sql)){
                if($result->num_rows > 0){
                    echo '<table class="table table-bordered table-striped">';
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th>#</th>";
                                echo "<th>Student ID</th>";
                                echo "<th>Course ID</th>";
                                echo "<th>Year</th>";
                                echo "<th>Semester</th>";
                                echo "<th>Grade</th>";
                                echo "<th>Action</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($row = $result->fetch_array()){
                            echo "<tr>";
                                echo "<td>" . $row['ID_schedule'] . "</td>";
                                echo "<td>" . $row['ID_student'] . "</td>";
                                echo "<td>" . $row['ID_course'] . "</td>";
                                echo "<td>" . $row['sched_yr'] . "</td>";
                                echo "<td>" . $row['sched_sem'] . "</td>";
                                echo "<td>" . $row['grade_letter'] . "</td>";
                                echo "<td>";
                                    echo '<a href="schedules/read-sch.php?id='. $row['ID_schedule'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                    echo '<a href="schedules/update-sch.php?id='. $row['ID_schedule'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                    echo '<a href="schedules/del-sch.php?id='. $row['ID_schedule'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
                                echo "</td>";
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
            
            // Close connection
            $db->close();
            ?>
        </div>
    </div>        
</div>

<?php require_once 'footer.php'; ?>