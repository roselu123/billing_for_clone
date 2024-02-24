<?php
include "../db.php";

// Check if the ID parameter is set
if(isset($_POST['id'])) {
    // Sanitize the input to prevent SQL injection
    $id = mysqli_real_escape_string($conn, $_POST['id']);

    // Update the database to mark the notification as paid
    $update_query = "UPDATE notif SET paid = 1 WHERE id = '{$id}'";

    if(mysqli_query($conn, $update_query)) {
        // Notification successfully marked as paid
        // Return a success response
        echo json_encode(array("success" => true));

        // Close the database connection
        mysqli_close($conn);
        exit(); // Stop script execution
    } else {
        // Error updating database
        echo json_encode(array("success" => false, "message" => "Error updating database."));
    }
} else {
    // ID parameter not set
    echo json_encode(array("success" => false, "message" => "ID parameter not set."));
}

// Close the database connection
mysqli_close($conn);
?>
<?php
// Include your database connection file
include "../db.php";

// Check if the request is an AJAX request
if(isset($_POST['id']) && isset($_POST['paid'])) {
    $id = $_POST['id'];
    $paid = $_POST['paid'];

    // Update the payment status in the database
    $update_query = "UPDATE notif SET paid = '$paid' WHERE id = '$id'";
    $result = mysqli_query($conn, $update_query);

    if($result) {
        // If the update was successful, return a success message
        echo "success";
    } else {
        // If the update failed, return an error message
        echo "error";
    }
} else {
    // If it's not an AJAX request, return an error message
    echo "invalid request";
}
?>


