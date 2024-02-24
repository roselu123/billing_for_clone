<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paid Patients</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto|Varela+Round">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <style>
        .table td,
        .table th {
            padding: 8px; /* Adjust padding as needed */
        }
        .table,
        .table th,
        .table td {
            border: none; /* Remove table border */
        }
        .container-xl {
            width: 80%; /* Adjust container width */
            margin: 0 auto; /* Center the container */
            padding-top: 50px; /* Add some top padding */
        }
        .table-striped tbody tr.paid-row {
            background-color: transparent; /* Remove background color for paid rows */
        }
        .table-striped tbody tr.paid-row:hover {
            background-color: lightblue; /* Change hover color to sky blue */
        }
    </style>
</head>
<body>

<div class="container-xl">
    <div class="table-responsive">
        <div class="table-wrapper">
            <div class="back-button">
                <a href="home.php" class="btn btn-primary"><i class="fa fa-arrow-left"></i></a>
            </div>

            <div class="table-title text-center">
                <h2>Paid <b>Patients</b></h2>
            </div>

            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th></th>
                        <th>Patient Name</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include "../db.php";

                    // Fetch paid patients data from the billing table where paid = 1
                    $query = "SELECT id, Patient_Name, Amount FROM notif WHERE paid = 1";
                    $result = mysqli_query($conn, $query);

                    // Display paid patients data in the table
                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr class='paid-row'>"; // Add the class for the green background
                            echo "<td></td>"; // Displaying the ID
                            echo "<td>{$row['Patient_Name']}</td>"; // Displaying the patient name
                            echo "<td>{$row['Amount']}</td>"; // Displaying the date of payment
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>No paid patients found.</td></tr>";
                    }

                    // Close the database connection
                    mysqli_close($conn);
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

</body>
</html>
