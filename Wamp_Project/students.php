<?php require_once 'header.php'; ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="mt-5 mb-3 clearfix">
                <h2 class="pull-left">Students Details</h2>
                <a href="students/new-stud.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Student</a>
            </div>
            <div class="mt-5 mb-3 clearfix">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <input type="Search" name="query" placeholder="Search Name or ID">
                    <input type="submit" value="Search">
                </form>
            </div>
            <?php
            
            // Attempt select query execution
            if(isset($_POST["query"]) && !empty($_POST["query"])){
                $query = trim($_POST["query"]);
                $sql = "SELECT * FROM t_students WHERE ID_student = '{$query}' OR fname LIKE '%{$query}%' OR lname LIKE '%{$query}%' ";
            }else {
                $sql = "SELECT * FROM t_students";
            }
            if($result = $db->query($sql)){
                if($result->num_rows > 0){
                    echo '<table class="table table-bordered table-striped">';
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th>ID</th>";
                                echo "<th>First Name</th>";
                                echo "<th>Last Name</th>";
                                echo "<th>Phone</th>";
                                echo "<th>Email</th>";
                                echo "<th>Status</th>";
                                echo "<th>Start Date</th>";
                                echo "<th>End Date</th>";
                                echo "<th>Action</th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        while($row = $result->fetch_array()){
                            echo "<tr>";
                                echo "<td>" . $row['ID_student'] . "</td>";
                                echo "<td>" . $row['fname'] . "</td>";
                                echo "<td>" . $row['lname'] . "</td>";
                                echo "<td>" . $row['phone'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "<td>" . $row['status'] . "</td>";
                                $sdate = date_create($row['start_dte']);
                                echo "<td>" . date_format($sdate, "Y/m/d") . "</td>";
                                $edate = date_create($row['end_dte']);
                                echo "<td>" . date_format($edate, "Y/m/d") . "</td>";
                                echo "<td>";
                                    echo '<a href="students/read-stud.php?id='. $row['ID_student'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye"></span></a>';
                                    echo '<a href="students/update-stud.php?id='. $row['ID_student'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil"></span></a>';
                                    echo '<a href="students/del-stud.php?id='. $row['ID_student'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash"></span></a>';
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