<?php require_once '../header.php'; ?>

<?php
	 
	// Define variables and initialize with empty values
	$fname = $lname = $phone = $email = "";
	$fname_err = $lname_err = $phone_err = $email_err = "";
	function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool {
	    return preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', $s);
	}

	function isValidTelephoneNumber(string $telephone, int $minDigits = 9, int $maxDigits = 14): bool {
	    if (preg_match('/^[+][0-9]/', $telephone)) { //is the first character + followed by a digit
	        $count = 1;
	        $telephone = str_replace(['+'], '', $telephone, $count); //remove +
	    }
	    
	    //remove white space, dots, hyphens and brackets
	    $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone); 

	    //are we left with digits only?
	    return isDigits($telephone, $minDigits, $maxDigits); 
	}

	function normalizeTelephoneNumber(string $telephone): string {
	    //remove white space, dots, hyphens and brackets
	    $telephone = str_replace([' ', '.', '-', '(', ')'], '', $telephone);
	    return $telephone;
	}
	 
	// Processing form data when form is submitted
	if(isset($_POST["id"]) && !empty($_POST["id"])){

		$id = $_POST["id"];
	    // Validate name
	    $input_fname = trim($_POST["fname"]);
	    if(empty($input_fname)){
	        $fname_err = "Please enter first name.";
	    } elseif(!filter_var($input_fname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
	        $fname_err = "Please enter a valid name.";
	    } else{
	        $fname = $input_fname;
	    }

	    $input_lname = trim($_POST["lname"]);
	    if(empty($input_lname)){
	        $lname_err = "Please enter last name.";
	    } elseif(!filter_var($input_lname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
	        $lname_err = "Please enter a valid name.";
	    } else{
	        $lname = $input_lname;
	    }

	    $input_phone = trim($_POST["phone"]);
	    if(empty($input_phone)){
	        $phone_err = "Please enter a phone number.";
	    } elseif(!isValidTelephoneNumber($input_phone)){
	        $phone_err = "Please enter a valid phone number.";
	    } else{
	        $phone1 = normalizeTelephoneNumber($input_phone);
	        $phone = sprintf("(%s) %s-%s", substr($phone1, 2, 3), substr($phone1, 5, 3), substr($phone1, 8));

	    }

	    $input_email = trim($_POST["email"]);
	    if(empty($input_email)){
	        $email_err = "Please enter an email.";
	    } elseif(!filter_var($input_email, FILTER_VALIDATE_EMAIL)){
	        $email_err = "Please enter a valid email.";
	    } else{
	        $email = $input_email;
	    }
	    
	    // Validate status
	    $status = trim($_POST["status"]);
	    
	    // Validate salary
	    $input_startd = trim($_POST["startd"]);
	    $startd = $input_startd;
	    
	    $input_endd = trim($_POST["endd"]);
	    $endd = $input_endd;
	    
	    // Check input errors before inserting in database
	    if(empty($fname_err) && empty($lname_err) && empty($phone_err) && empty($email_err)){
	        // Prepare an insert statement
	        $sql1 = "UPDATE t_students SET fname=?, lname=?, phone=?, email=?, status=?, start_dte=?, end_dte=? WHERE ID_student = ?";
	 
	        if($stmt = $db->prepare($sql1)){
	            // Bind variables to the prepared statement as parameters
	            $stmt->bind_param("sssssssi", $param_fname, $param_lname, $param_phone, $param_email, $param_status, $param_startd, $param_endd, $param_id);
	            
	            // Set parameters
	            $param_fname = $fname;
	            $param_lname = $lname;
	            $param_phone = $phone;
	            $param_email = $email;
	            $param_status = $status;
	            $param_startd = $startd;
	            $param_endd = $endd;
	            $param_id = $id;
	            
	            // Attempt to execute the prepared statement
	            if($stmt->execute()){
	                // Records created successfully. Redirect to landing page
	                header("location: ../students.php");
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
		if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
			$id = trim($_GET["id"]);
			$sql = "SELECT * FROM t_students WHERE ID_student = ?";

			if ($stmt = $db->prepare($sql)) {
				$stmt->bind_param("i", $param_id);

				$param_id = $id;

				if ($stmt->execute()) {
					$result = $stmt->get_result();

					if ($result->num_rows == 1) {
						$row = $result->fetch_array(MYSQLI_ASSOC);
						$fname = $row["fname"];
		                $lname = $row["lname"];
		                $phone = $row["phone"];
		                $email = $row["email"];
		                $status = $row["status"];
		                $startd = $row["start_dte"];
		                $endd = $row["end_dte"];
		                // $sdate = date_create($startd);
		                // $startd = date_format($sdate, "m/d/Y");
		                // $edate = date_create($endd);
		                // $endd = date_format($edate, "m/d/Y");
					}else {
						header("location: ../error.php");
						exit();
					}
				} else {
					echo "Oops! Something went wrong. Please try again later.";
				}

			}
			$stmt->close();

			$db->close();
		} else {
			header("location: ../error.php");
			exit();
		}
	}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mt-5">Update Student Record</h2>
            <p>Please edit the input values and submit to update the student record.</p>
            <form action="<?php echo htmlspecialchars(basename($_SERVER["REQUEST_URI"])); ?>" method="post">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="fname" class="form-control <?php echo (!empty($fname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $fname; ?>">
                    <span class="invalid-feedback"><?php echo $fname_err;?></span>
                </div>
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="lname" class="form-control <?php echo (!empty($lname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lname; ?>">
                    <span class="invalid-feedback"><?php echo $lname_err;?></span>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="phone" name="phone" class="form-control <?php echo (!empty($phone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phone; ?>">
                    <span class="invalid-feedback"><?php echo $phone_err;?></span>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                    <span class="invalid-feedback"><?php echo $email_err;?></span>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="custom-select">
                    	<option value="1" <?php if ($status == '1'){ echo "selected";}?>>Yes</option>
                    	<option value="0" <?php if ($status == '0'){ echo "selected";}?>>No</option>
                    </select>
                    
                </div>
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="startd" class="form-control" placeholder="yyyy/mm/dd" value="<?php echo $startd; ?>">
                </div>
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="endd" class="form-control" placeholder="yyyy/mm/dd" value="<?php echo $endd; ?>">
                </div>
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="submit" class="btn btn-primary" value="Update">
                <a href="../students.php" class="btn btn-secondary ml-2">Cancel</a>
            </form>
        </div>
    </div>        
</div>

<?php require_once '../footer.php'; ?>