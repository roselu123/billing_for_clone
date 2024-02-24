<?php
include "../db.php";

// Mark notification as read if it has been viewed
if(isset($_GET['notification_clicked'])) {
    $currentDate = date('Y-m-d');
    // Update notification_read to 1 for notifications with Due_Date equal to current date in the billing table
    $update_query_billing = "UPDATE notif SET notification_read = 1 WHERE Due_Date = '{$currentDate}'";
    mysqli_query($conn, $update_query_billing);
}


// Calculate two days ahead of the current date
$twoDaysAhead = date('Y-m-d', strtotime($currentDate . '+2 days'));

// Fetch notifications from billing table where the due date is two days ahead of the current date
$query_billing = "SELECT id, Patient_Name, Amount, Due_Date
                FROM billing 
                WHERE Due_Date = '{$twoDaysAhead}' AND notification_read = 0";

$result_billing = mysqli_query($conn, $query_billing);

// Insert retrieved data into the notif table for notifications due two days ahead
// Insert retrieved data into the notif table for notifications due two days ahead
while ($row = mysqli_fetch_assoc($result_billing)) {
    $patientName = $row['Patient_Name'];
    $dueDate = $row['Due_Date'];
    
    // Check if a notification for the patient already exists in the notif table
    $existingNotificationQuery = "SELECT id FROM notif WHERE Patient_Name = '{$patientName}' AND Due_Date = '{$dueDate}'";
    $existingNotificationResult = mysqli_query($conn, $existingNotificationQuery);
    
    if (mysqli_num_rows($existingNotificationResult) > 0) {
        // Update existing notification instead of inserting a new one
        $updateQuery = "UPDATE notif SET Amount = '{$row['Amount']}', no_of_notifications = no_of_notifications  WHERE Patient_Name = '{$patientName}' AND Due_Date = '{$dueDate}'";
        mysqli_query($conn, $updateQuery);
    } else {
        // Insert a new notification if it doesn't already exist
        $insertQuery = "INSERT INTO notif (Patient_Name, Amount, Due_Date, no_of_notifications, notification_read) 
                        VALUES ('{$patientName}', '{$row['Amount']}', '{$dueDate}', 0, 0)";
        mysqli_query($conn, $insertQuery);
    }
}


// Fetch notifications from notif table for display
$query_notif = "SELECT id, Patient_Name, Amount, Due_Date, paid 
                FROM notif 
                WHERE Due_Date = '{$twoDaysAhead}' AND notification_read = 0";
$result_notif = mysqli_query($conn, $query_notif);

// Close the database connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="notif.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <title>Notifications</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding-top: 10px; /* Adjusted padding */
            padding-bottom: 10px; /* Adjusted padding */
        }
        .section-50 {
            padding: 0 10px; /* Adjusted padding */
        }
        .heading-line {
            border-bottom: 2px solid #007bff;
            padding-bottom: 5px; /* Reduced padding */
            margin-bottom: 20px; /* Reduced margin */
            margin-top: 20px; /* Added margin-top */
            text-align: center;
            font-size: 20px; /* Reduced font size */
        }
        .notification-card {
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px; /* Reduced padding */
            margin-left: 50px;
            margin-bottom: 10px; /* Reduced margin */
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); /* Reduced shadow */
            width: 600px;
            position: relative; /* Added position relative */
        }
        .notification-card h5 {
            margin-top: 0;
            margin-bottom: 5px; /* Reduced margin */
            color: #007bff;
            font-size: 16px; /* Reduced font size */
        }
        .notification-card p {
            margin-top: 0;
            margin-bottom: 5px; /* Reduced margin */
            font-size: 14px; /* Reduced font size */
            line-height: 1.3; /* Reduced line height */
        }
        .btn-success {
            padding: 5px 10px; /* Reduced padding */
            font-size: 14px; /* Reduced font size */
        }
        .paid-message {
            margin-top: 0;
            margin-bottom: 5px; /* Reduced margin */
            color: green;
            font-size: 14px; /* Reduced font size */
        }
        .close-btn {
            position: absolute;
            top: 5px;
            right: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <section class="section-50">
        <div class="container">
            <h3 class="m-b-50 heading-line">Notifications</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="notification-ui_dd-content">
                        <?php
                        if ($result_notif && mysqli_num_rows($result_notif) > 0) {
                            while ($row = mysqli_fetch_assoc($result_notif)) {
                                $id = $row['id'];
                                $name = $row['Patient_Name'];
                                $payment = $row['Amount'];
                                $dueDate = $row['Due_Date'];

                                echo "<div class='notification-card' data-id='{$id}'>";
                                echo "<span class='close-btn' onclick='removeNotification($id)'>&times;</span>"; // Close button
                                echo "<h5>Patient Name: {$name}</h5>";
                                echo "<p>Payment Amount: {$payment}</p>";
                                echo "<p>Due Date: {$dueDate}</p>";
                                echo "<div class='text-center' style='justify-content: space-between; display: flex;'>";
                                if ($row['paid'] == 0) {
                                    echo "<button type='button' class='btn btn-success' data-id='{$id}' onclick='markAsPaid($id)'> Fully Paid</button>";
                                } else {
                                    echo "<p class='paid-message'>Patient has already paid.</p>";
                                    // Hide the notification card if the patient has paid
                                    echo "<script>document.querySelector(`div[data-id='${id}']`).style.display = 'none';</script>";
                                }
                                echo "</div>";
                                echo "</div>";
                            }
                        } else {
                            echo "<p>No notifications to display.</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="successModalLabel">Successfully Paid</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Payment has been successfully processed.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="closeModalAndRemoveNotification()">OK</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script>
    <script>
    function markAsPaid(id) {
        id = parseInt(id);
        var button = document.querySelector(`button[data-id='${id}']`);
        if (button) {
            button.disabled = true;
            $('#successModal').modal('show');
            $('.modal-body').attr('data-notification-id', id);
        } else {
            console.error('Button element not found');
        }
    }

    function closeModalAndRemoveNotification() {
        $('#successModal').modal('hide');
        var id = parseInt($('.modal-body').data('notification-id'));
        var notificationCard = document.querySelector(`div[data-id='${id}']`);
        if (notificationCard) {
            notificationCard.remove();
            // Send AJAX request to update the database
            $.ajax({
                url: 'action.php', // Replace with the file path to your PHP script for updating the database
                method: 'POST',
                data: { id: id }, // Pass the ID of the notification to be removed
                success: function(response) {
                    console.log('Notification removed from the database.');
                    // Remove the notification card from the UI
                    notificationCard.remove();
                },
                error: function(xhr, status, error) {
                    console.error('Error removing notification from the database:', error);
                }
            });
        }
    }

    function removeNotification(id) {
        var notificationCard = document.querySelector(`div[data-id='${id}']`);
        if (notificationCard) {
            notificationCard.remove();
            // Send AJAX request to update the database
            $.ajax({
                url: 'action.php', // Replace with the file path to your PHP script for updating the database
                method: 'POST',
                data: { id: id }, // Pass the ID of the notification to be removed
                success: function(response) {
                    console.log('Notification removed from the database.');
                },
                error: function(xhr, status, error) {
                    console.error('Error removing notification from the database:', error);
                }
            });
        }
    }
    </script>
</body>
</html>