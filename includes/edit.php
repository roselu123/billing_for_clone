<?php
include "../header.php";
include "../db.php";

$date = "";
$patient = "";
$guarantor = ""; 
$address = "";
$contact = "";
$amount = "";
$due = ""; 
$collateral = "";
$p_note = "";
$c_images = array(); // Array to store multiple collateral images filenames

if(isset($_GET['user_id'])) {
    $userid = $_GET['user_id'];
    $query = "SELECT * FROM billing WHERE id = '{$userid}'";
    $view_billing = mysqli_query($conn, $query); 

    if(mysqli_num_rows($view_billing) > 0) { 
        $row = mysqli_fetch_assoc($view_billing);
        $date = $row['Date'];
        $patient = $row['Patient_Name'];
        $guarantor = $row['Name_Gaurantor'];
        $address = $row['Address'];
        $contact = $row['Contact'];
        $amount = $row['Amount'];
        $due = $row['Due_Date']; 
        $collateral = $row['Collateral_Given'];
        $p_note = $row['Promissory_Note'];
        // Split the comma-separated list of filenames into an array for multiple collateral images
        $c_images = explode(",", $row['Collateral_Image']);
    } else {
        header("Location: home.php");
        exit();
    }
} else {
    header("Location: home.php");
    exit();
}

if(isset($_POST['update'])) {
    $date = date('Y-m-d', strtotime($_POST['Date']));
    $patient = mysqli_real_escape_string($conn, $_POST['patient']);
    $guarantor = mysqli_real_escape_string($conn, $_POST['guarantor']);
    $address = mysqli_real_escape_string($conn, $_POST['address']); 
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $amount = mysqli_real_escape_string($conn, $_POST['amount']); 
    $due = date('Y-m-d', strtotime($_POST['due']));
    $collateral = mysqli_real_escape_string($conn, $_POST['collateral']); 
    $p_note_filename = ""; // Variable to store the file name for promissory note
    $c_images_filenames = implode(",", $c_images); // Convert array to comma-separated string

    
    // Check if promissory note file is uploaded
   
    // Update the database with the file names
    $query = "UPDATE billing SET 
              Date = '{$date}', 
              Patient_Name = '{$patient}', 
              Name_Gaurantor = '{$guarantor}', 
              Address = '{$address}', 
              Contact = '{$contact}',  
              Amount = '{$amount}',
              Due_Date = '{$due}',
              Collateral_Given = '{$collateral}',
              Promissory_Note = '{$p_note_filename}',
              Collateral_Image = '{$c_images_filenames}'
              WHERE id = '{$userid}'";

    $update_user = mysqli_query($conn, $query);

    if($update_user) {
        echo "<div class='alert alert-success' role='alert'>Information updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger' role='alert'>Error updating record: " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Update Record</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
  
<div class="container">
    <div class="title">Update Record</div>
    <div class="content">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="user-details">
                <div class="input-box">
                    <span class="details">Date</span>
                    <input type="date" name="Date" value="<?php echo date('Y-m-d', strtotime($date)) ?>">
                </div>
                <div class="input-box">
                    <span class="details">Patient Name</span>
                    <input type="text" name="patient" placeholder="Enter Patient Name" value ="<?php echo $patient ?>">
                </div>
                <div class="input-box">
                    <span class="details">Name of Guarantor</span>
                    <input type="text" name="guarantor" placeholder="Enter Name of Guarantor" value ="<?php echo $guarantor ?>">
                </div>
                <div class="input-box">
                    <span class="details">Address</span>
                    <input type="text" name="address" placeholder="Enter Address" value ="<?php echo $address ?>">
                </div>
                <div class="input-box">
                    <label for="lastName"> Contact </label>
                    <input  type="tel" name="contact" placeholder="Enter Contact Number" value ="<?php echo $contact ?>">
                </div>
                <div class="input-box">
                    <span class="details">Amount</span>
                    <input type="text" name="amount" placeholder="Enter Amount" value ="<?php echo $amount ?>">
                </div>
                <div class="input-box">
                    <span class="details">Due Date</span>
                    <input type="date" name="due" value="<?php echo date('Y-m-d', strtotime($due)) ?>">
                </div>
                
              <div class="input-box">
   
   
    
</div>

               
            </div>
            <div class="button">
                <input type="submit" name="update" value="Submit">
            </div>
            <div class="button1">     
                <a href="home.php" class="btn btn-warning mt-3"> Back </a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
