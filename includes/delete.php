<?php
// Include the file containing the database connection
include "../db.php";

if(isset($_GET['delete']))
{
    $userid = $_GET['delete'];

    // SQL query to delete data from the billing table where id = $userid
    $query = "DELETE FROM billing WHERE id = '{$userid}'";
    
    // Perform the delete query
    $delete_query = mysqli_query($conn, $query);
    
    // Check if the query was successful
    if ($delete_query) {
        // Redirect to the home page after successful deletion
        header("Location: home.php");
        exit(); // Terminate script execution after redirection
    } else {
        // Handle the case where the delete query failed
        echo "Error deleting record: " . mysqli_error($conn);
    }
}

// Close the database connection
mysqli_close($conn);

// Include the footer file
include "footer.php";
?>
