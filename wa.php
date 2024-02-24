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
    $c_images_filenames = array(); // Array to store the file names for collateral images
    
    // Check if promissory note file is uploaded
    if(isset($_FILES['Promissory_Note']) && $_FILES['Promissory_Note']['error'] === UPLOAD_ERR_OK) {
        $p_note_filename = $_FILES['Promissory_Note']['name'];
        move_uploaded_file($_FILES['Promissory_Note']['tmp_name'], 'promissory_notes/' . $p_note_filename);
    } else {
        // Retain the existing file name if not updated
        $p_note_filename = $_POST['p_note_filename'] ?? '';
    }
    
    // Check if collateral images files are uploaded
    if(isset($_FILES['Collateral_Image']) && !empty($_FILES['Collateral_Image']['name'][0])) {
        // Loop through each uploaded collateral image
        foreach($_FILES['Collateral_Image']['tmp_name'] as $key => $tmp_name) {
            $c_image_filename = $_FILES['Collateral_Image']['name'][$key];
            move_uploaded_file($tmp_name, 'collateral_images/' . $c_image_filename);
            $c_images_filenames[] = $c_image_filename;
        }
    } else {
        // Retain the existing file names if not updated
        $c_images_filenames = $c_images;
    }
    
    // Combine the filenames into a comma-separated list
    $c_images_string = implode(",", $c_images_filenames);
    
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
              Collateral_Image = '{$c_images_string}'
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
                    <span class="details">Collateral Given</span>
                    <input type="text" name="collateral" placeholder="Enter Collateral Given" value ="<?php echo $collateral ?>">
                </div>
                <div class="input-box">
                    <span class="details">Upload Promissory Notes</span>
                    <input type="file" name="Promissory_Note" accept="image/*">
                    <?php if (!empty($p_note)) {
                        $imagePath = "promissory_notes/{$p_note}"; // Constructing the path to the image
                        echo "<img src='{$imagePath}' alt='Promissory Note' style='max-width: 300px; margin-top: 5px;'>"; // Displaying the image directly with increased size and margin
                    } else {
                        echo "No Image Uploaded"; // Displaying a message if no image is uploaded
                    } ?>
                </div>
              <div class="input-box">
    <span class="details">Upload Collateral Given</span>
    <input type="file" name="Collateral_Image[]" accept="image/*" multiple>
    <?php 
    // Display existing collateral images
    if (!empty($c_images)) {
        foreach ($c_images as $c_image) {
            $imagePath = "collateral_images/{$c_image}";
            echo "<img src='{$imagePath}' alt='Collateral Given' style='max-width: 300px; margin-top: 10px;'>"; 
        }
    } else {
        echo "No Images Uploaded"; 
    }
    ?>
</div>

                <!-- Add hidden input fields to retain file names -->
                <input type="hidden" name="p_note_filename" value="<?php echo htmlspecialchars($p_note); ?>">
                <input type="hidden" name="c_image_filename" value="<?php echo htmlspecialchars($c_image); ?>">
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
