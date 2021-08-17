<?php require_once 'header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="mt-5 mb-3 clearfix">
                <h2 class="pull-left">Courses Details</h2>
                <a href="courses/new-course.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Course</a>
            </div>
            <div class="mt-5 mb-3 clearfix">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="Search" name="query" placeholder="Search Course Code">
                    <input type="submit" value="Search">
                </form>
            </div>
            <?php
            
            // Attempt select query execution
            if(isset($_POST["query"]) && !empty($_POST["query"])){
                $query = trim($_POST["query"]);
                $sql = "SELECT * FROM t_courses WHERE course_code = '{$query}'";
            }else {
                $sql = "SELECT * FROM t_courses";
            }
            
            if($result = $db->query($sql)){
                if($result->num_rows > 0){
                    echo '<table class="table table-bordered table-striped">';
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th>#</th>";
                                echo "<th>Course Code</th>";
                                echo "<th>Course Description</th>";
                                echo "<th>Action</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($row = $result->fetch_array()){
                            echo "<tr>";
                                echo "<td>" . $row['ID_course'] . "</td>";
                                echo "<td>" . $row['course_code'] . "</td>";
                                echo "<td>" . $row['course_desc'] . "</td>";
                                echo "<td>";
                                    echo '<a href="courses/read-course.php?id='. $row['ID_course'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                    echo '<a href="courses/update-course.php?id='. $row['ID_course'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                    echo '<a href="courses/del-course.php?id='. $row['ID_course'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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