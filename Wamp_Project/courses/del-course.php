<?php require_once '../header.php'; ?>

<?php
// Process delete operation after confirmation
if(isset($_POST["id"]) && !empty($_POST["id"])){
    
    // Prepare a delete statement
    $sql = "DELETE FROM t_courses WHERE ID_course = ?";
    
    if($stmt = mysqli_prepare($db, $sql)){
        // Bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        // Set parameters
        $param_id = trim($_POST["id"]);
        
        // Attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            // Records deleted successfully. Redirect to landing page
            header("location: ../courses.php");
            exit();
        } else{
            echo "Oops! Something went wrong. Please try again later.";
        }
    }
     
    // Close statement
    mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($db);
} else{
    // Check existence of id parameter
    if(empty(trim($_GET["id"]))){
        // URL doesn't contain id parameter. Redirect to error page
        header("location: ../error.php");
        exit();
    }
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mt-5 mb-3">Delete Course Record</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="alert alert-danger">
                    <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>"/>
                    <p>Are you sure you want to delete this course record?</p>
                    <p>
                        <input type="submit" value="Yes" class="btn btn-danger">
                        <a href="../courses.php" class="btn btn-secondary">No</a>
                    </p>
                </div>
            </form>
        </div>
    </div>        
</div>

<?php require_once '../footer.php'; ?>