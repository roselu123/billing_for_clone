<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Billing Management</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        body {
            color: #566787;
            background: #f5f5f5;
            font-family: 'Varela Round', sans-serif;
            font-size: 13px;
            padding: 20px;
        }

        .patient-details {
            margin-bottom: 20px;
        }

        .patient-details strong {
            font-weight: bold;
        }

        .collateral-images {
            margin-top: 10px;
        }

        .collateral-images a {
            margin-right: 10px;
        }

        .table-noborder {
            border-collapse: collapse;
        }

        .table-noborder th,
        .table-noborder td {
            border: none;
        }

        .table-wrapper {
            margin: 0 auto;
            overflow-x: auto;
			margin-left: 10%;
        }

        .table-title {
            margin-left: 20%;
        }

        .table-noborder td:hover {
            background-color: #add8e6;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
            color: #000;
        }

        .back-button {
            text-align: left;
            margin-top: 20px;
            margin-bottom: 20px;
            margin-left: 20px;
        }
    </style>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
</head>
<body>

<?php
include "../db.php";
?>
<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
		<div class="back-button">
			<a href="home.php" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
		</div>

            <div class="table-title">
                <h2><b>Patients Information</b> <br> </h2>
            </div>
            <table class="table table-noborder">
                <?php
                include "../db.php"; // Make sure this file includes your database connection

                // Process form data when form is submitted
                if (isset($_GET['user_id'])) {
                    $id = $_GET['user_id'];

                    // SQL query to fetch the data where Patient_Name matches $patientName
                    $query = "SELECT * FROM billing WHERE id = '{$id}'";
                    $view_billing = mysqli_query($conn, $query);

                    // Fetch and display the data
                    while ($row = mysqli_fetch_assoc($view_billing)) {

                        $date = $row['Date'];
                        $patient = $row['Patient_Name'];
                        $gaurantor = $row['Name_Gaurantor'];
                        $address = $row['Address'];
                        $contact = $row['Contact'];
                        $amount = $row['Amount'];
                        $due = $row['Due_Date'];
                        $collateral = $row['Collateral_Given'];
                        $c_images = explode(",", $row['Collateral_Image']); // Split the comma-separated list of filenames into an array
						$orcr_images = explode(",", $row['OR_CR']); 
						$titles_images = explode(",", $row['Titles']); 
						$p_note = $row['Promissory_Note'];
                        $s_note = $row['Statement_of_Account'];
                ?>
                <tr>
                    <th><h5>Date: <?php echo $date; ?> </h5></th>
                </tr>
                <tr>
                    <th><h5>Patient Name</h5></th>
                    <th><h5>Contact No.</h5></th> 
                </tr>
                <tr>
                    <td><h6><?php echo $patient; ?></h6></td>
                    <td><h6><?php echo $contact; ?></h6></td>    
                </tr>
                <tr>
                    <th><h5>Name of Guarantor</h5></th>
                    <th><h5>Amount</h5></th>
                </tr>
                <tr>
                    <td><h6><?php echo $gaurantor; ?></h6></td>   
                    <td><h6><?php echo $amount; ?></h6></td>                                
                </tr>
                <tr>
                    <th><h5>Address</h5></th>
                    <th><h5>Due Date</h5></th>
                </tr>
                <tr>
                    <td><h6><?php echo $address; ?></h6></td>
                    <td><h6><?php echo $due; ?></h6></td>
                </tr>
				<tr>
    <th><h5>ID Image</h5></th>
    <th><h5>OR/CR Image</h5></th>
</tr>
<tr>  
    <td>
        <?php
        if (!empty($c_images)) {
            // Display each image filename as a clickable link
            foreach ($c_images as $c_image) {
                // Trim the filename to remove leading and trailing spaces
                $c_image = trim($c_image);
                $imagePath = "collateral_images/{$c_image}"; // Construct the path to the image
                echo "<a href='{$imagePath}' target='_blank'>{$c_image}</a><br>"; // Creating a link to view the image
            }
        } else {
            echo "No Images Uploaded"; // Displaying a message if no images are uploaded
        }
        ?>
    </td>
    <td>
        <?php
        if (!empty($orcr_images)) {
            // Display each image filename as a clickable link
            foreach ($orcr_images as $orcr_image) {
                // Trim the filename to remove leading and trailing spaces
                $orcr_image = trim($orcr_image);
                $imagePath = "orcr_images/{$orcr_image}"; // Construct the path to the image
                echo "<a href='{$imagePath}' target='_blank'>{$orcr_image}</a><br>"; // Creating a link to view the image
            }
        } else {
            echo "No Images Uploaded"; // Displaying a message if no images are uploaded
        }
        ?>
    </td>
</tr>

<tr>
    <th><h5>TITLES Image</h5></th>
</tr>
<tr>  
    <td>
        <?php
        if (!empty($titles_images)) {
            // Display each image filename as a clickable link
            foreach ($titles_images as $titles_image) {
                // Trim the filename to remove leading and trailing spaces
                $titles_image = trim($titles_image);
                $imagePath = "titles_images/{$titles_image}"; // Construct the path to the image
                echo "<a href='{$imagePath}' target='_blank'>{$titles_image}</a><br>"; // Creating a link to view the image
            }
        } else {
            echo "No Images Uploaded"; // Displaying a message if no images are uploaded
        }
        ?>
    </td>
</tr>
                <tr>
                    <th><h5>Promissory Note</h5></th>
                    <th><h5>Statement of Account</h5></th>                  
                </tr>
                <tr>
                    <td>
                        <?php
                        if (!empty($p_note)) {
                            $imagePath = "promissory_notes/{$p_note}"; // Constructing the path to the image
                            echo "<a href='{$imagePath}' target='_blank'>View Image</a>"; // Creating a link to view the image
                        } else {
                            echo "No Image Uploaded"; // Displaying a message if no image is uploaded
                        }
                        ?>
                    </td>
                    <td colspan="3">
                        <?php
                        if (!empty($s_note)) {
                            $imagePath = "statement_of_account/{$s_note}"; // Constructing the path to the image
                            echo "<a href='{$imagePath}' target='_blank'>View Image</a>"; // Creating a link to view the image
                        } else {
                            echo "No Image Uploaded"; // Displaying a message if no image is uploaded
                        }
                        ?>
                    </td>
                </tr>
                <?php
                    }
                }
                ?>
            </table>
        </div>
    </div>
</div>

</body>
</html>
