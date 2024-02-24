<?php 
// Include database connection
include "../header.php";
include "../db.php";

if(isset($_POST['create'])) {
    // Retrieve form data
    $date = date('Y-m-d', strtotime($_POST['Date']));
    $patient = isset($_POST['patient']) ? $_POST['patient'] : '';               
    $guarantor = isset($_POST['guarantor']) ? $_POST['guarantor'] : '';        
    $address = isset($_POST['address']) ? $_POST['address'] : ''; 
    $contact = isset($_POST['contact']) ? $_POST['contact'] : ''; 
    $amount = isset($_POST['amount']) ? $_POST['amount'] : ''; 
    $due_date = date('Y-m-d', strtotime($_POST['Due_Date']));
    $collateral_types = isset($_POST['collateral_type']) ? $_POST['collateral_type'] : array();  

    // Initialize variables to store uploaded file names
    $promissory_note_filename = '';
    $collateral_image_filenames = array();
    $orcr_image_filenames = array(); // Initialize array for OR/CR images
    $titles_image_filenames = array(); // Initialize array for Titles images
    $statement_of_account_filename = '';

    if(isset($_FILES['Promissory_Note'])) {
        // Define target directory for uploaded files
        $target_directory = "promissory_notes/";
        // Get the name of the uploaded file
        $promissory_note_filename = basename($_FILES["Promissory_Note"]["name"]);
        // Define the target path for the uploaded file
        $target_path = $target_directory . $promissory_note_filename;
        
        // Move the uploaded file to the target directory
        if(move_uploaded_file($_FILES["Promissory_Note"]["tmp_name"], $target_path)) {
            // File upload successful, proceed to save the filename in the database
        } else {
            echo "Sorry, there was an error uploading your file.";
            // You can handle this error according to your requirements
        }
    }
    
    // Process Collateral Image upload
    if(isset($_FILES['Collateral_Image'])) {
        // Define target directory for uploaded files
        $target_directory = "collateral_images/";
        
        // Loop through each uploaded collateral image
        foreach($_FILES['Collateral_Image']['tmp_name'] as $key => $tmp_name) {
            // Get the name of the uploaded file
            $collateral_image_filename = basename($_FILES["Collateral_Image"]["name"][$key]);
            // Define the target path for the uploaded file
            $target_path = $target_directory . $collateral_image_filename;
            
            // Move the uploaded file to the target directory
            if(move_uploaded_file($tmp_name, $target_path)) {
                $collateral_image_filenames[] = $collateral_image_filename;
            } else {
                echo "Sorry, there was an error uploading your file.";
                // You can handle this error according to your requirements
            }
        }
    }

    // Process OR/CR Image upload
    if(isset($_FILES['OR_CR'])) {
        // Define target directory for uploaded files
        $target_directory = "orcr_images/";
        
        // Loop through each uploaded OR/CR image
        foreach($_FILES['OR_CR']['tmp_name'] as $key => $tmp_name) {
            // Get the name of the uploaded file
            $orcr_image_filename = basename($_FILES["OR_CR"]["name"][$key]);
            // Define the target path for the uploaded file
            $target_path = $target_directory . $orcr_image_filename;
            
            // Move the uploaded file to the target directory
            if(move_uploaded_file($tmp_name, $target_path)) {
                $orcr_image_filenames[] = $orcr_image_filename;
            } else {
                echo "Sorry, there was an error uploading your file.";
                // You can handle this error according to your requirements
            }
        }
    }

    // Process Titles Image upload
    if(isset($_FILES['Titles_Image'])) {
        // Define target directory for uploaded files
        $target_directory = "titles_images/";
        
        // Loop through each uploaded Titles image
        foreach($_FILES['Titles_Image']['tmp_name'] as $key => $tmp_name) {
            // Get the name of the uploaded file
            $titles_image_filename = basename($_FILES["Titles_Image"]["name"][$key]);
            // Define the target path for the uploaded file
            $target_path = $target_directory . $titles_image_filename;
            
            // Move the uploaded file to the target directory
            if(move_uploaded_file($tmp_name, $target_path)) {
                $titles_image_filenames[] = $titles_image_filename;
            } else {
                echo "Sorry, there was an error uploading your file.";
                // You can handle this error according to your requirements
            }
        }
    }

  // Combine Collateral filenames into a single string
$collateral_images_string = !empty($collateral_image_filenames) ? implode(", ", $collateral_image_filenames) : '';

// Combine OR/CR filenames into a single string
$orcr_images_string = !empty($orcr_image_filenames) ? implode(", ", $orcr_image_filenames) : '';

// Combine Titles filenames into a single string
$titles_images_string = !empty($titles_image_filenames) ? implode(", ", $titles_image_filenames) : '';


// SQL query to insert user data and uploaded filenames into the database
$query = "INSERT INTO billing (Date, Patient_Name, Name_Gaurantor, Address, Contact, Amount, Due_Date, Promissory_Note, Collateral_Image, OR_CR, Titles, Statement_of_Account) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

// Prepare the SQL statement
$stmt = mysqli_prepare($conn, $query);

// Check if prepare statement succeeded
if ($stmt) {
    // Bind parameters
    mysqli_stmt_bind_param($stmt, "ssssisssssss", $date, $patient, $guarantor, $address, $contact, $amount, $due_date, $promissory_note_filename, $collateral_images_string, $orcr_images_string, $titles_images_string, $statement_of_account_filename);

    // Execute the statement
    $result = mysqli_stmt_execute($stmt);

    // Check if the query executed successfully
    if ($result) {
        // Display a JavaScript alert to notify the user
        echo "<script type='text/javascript'>alert('User added successfully!'); window.location.href = 'home.php';</script>";
        // Redirect the user back to the home page after displaying the alert
        exit(); // Exit to prevent further execution of the script
    } else {
        echo "Something went wrong: " . mysqli_error($conn);
    }

    // Close statement
    mysqli_stmt_close($stmt);
} else {
    // Handle prepare statement error
    echo "Prepare statement error: " . mysqli_error($conn);
}

}

// Close connection
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <title>Responsive Registration Form | CodingLab</title>
    <link rel="stylesheet" href="style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
  
<div class="container">
    <div class="title">Add New Patient</div>
    <div class="content">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="user-details">
                 <div class="input-box">
                    <span class="details">Date</span>
                    <input type="datetime-local" name="Date" required>
                </div>
                <div class="input-box">
                    <span class="details">Patient Name</span>
                    <input type="text" name="patient" placeholder="Enter Patient Name" required>
                </div>
                <div class="input-box">
                    <span class="details">Name of Guarantor</span>
                    <input type="text" name="guarantor" placeholder="Enter Name of Guarantor" required>
                </div>
                <div class="input-box">
                    <span class="details">Address</span>
                    <input type="text" name="address" placeholder="Enter Address" required>
                </div>
                <div class="input-box">
                    <label for="lastName"> Contact </label>
                    <input type="text" name="contact" placeholder="Enter Contact Number" required>
                </div>
                <div class="input-box">
                    <span class="details">Amount</span>
                    <input type="text" name="amount" placeholder="Enter Amount" required>
                </div>
                
                <div class="input-box">
                    <span class="details">Due Date</span>
                    <input type="datetime-local" name="Due_Date" required>
                </div>

                <div class="input-box">
                    <span class="details">ID</span>
                    <div id="collateral-container">
                        <div class="collateral-item">
                            <input type="file" name="Collateral_Image[]" accept="image/*" >
                        </div>
                    </div>
                    <button type="button" onclick="addCollateral()">Add ID</button>
                </div>

                <div class="input-box">
                    <span class="details">OR/CR</span>
                    <div id="orcr-container">
                        <div class="orcr-item">
                            <input type="file" name="OR_CR[]" accept="image/*" multiple>
                        </div>
                    </div>
                    <button type="button" onclick="addORCR()">Add OR/CR</button>
                </div>

                <div class="input-box">
                    <span class="details">TITLES</span>
                    <div id="titles-container">
                        <div class="titles-item">
                            <input type="file" name="Titles_Image[]" accept="image/*" multiple>
                        </div>
                    </div>
                    <button type="button" onclick="addTitles()">Add TITLES</button>
                </div>

            <div class="input-box">
        <span class="details">Upload Promissory Notes</span>
      <input type="file" name="Promissory_Note" accept="image/*" >
            </div>         
            <div class="input-box">
         <span class="details">Upload Statement of Account</span>
     <input type="file" name="Statement_of_Account" accept="image/*" >
            </div>         
            </div>
            <div class="button">
                <input type="submit" name="create" value="Submit">
            </div>
            <div class="button1">     
                <a href="home.php" class="btn btn-warning mt-3"> Back </a>
            </div>
        </form>
    </div>
</div>

<script>
    function addCollateral() {
        const container = document.getElementById('collateral-container');
        const newItem = document.createElement('div');
        newItem.classList.add('collateral-item');
        newItem.innerHTML = `
            <input type="file" name="Collateral_Image[]" accept="image/*" required>
        `;
        container.appendChild(newItem);
    }
    
    function addORCR() {
        const container = document.getElementById('orcr-container');
        const newItem = document.createElement('div');
        newItem.classList.add('orcr-item');
        newItem.innerHTML = `
            <input type="file" name="OR_CR[]" accept="image/*" multiple required>
        `;
        container.appendChild(newItem);
    }

    function addTitles() {
        const container = document.getElementById('titles-container');
        const newItem = document.createElement('div');
        newItem.classList.add('titles-item');
        newItem.innerHTML = `
            <input type="file" name="Titles_Image[]" accept="image/*" multiple required>
        `;
        container.appendChild(newItem);
    }

</script>

</body>
</html>
