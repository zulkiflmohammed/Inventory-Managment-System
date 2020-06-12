<!-- profile.php - admin profile editing 
    Course: CSC235- prjFinal
    Written by: Zulkifl Mohammed
    Email: mohammez@csp.edu
    Written:    12/15/19
    Revised:   
    -->

<?php
//profile.php
ini_set('display_errors', E_ALL);
include('databaseConnection.php');
include('inventoryManagementLib.php');

if (!isset($_SESSION["id_user"])) {
    header("location:logIn.php");
}
$id_user = 1;
// $query = "SELECT * FROM administrator WHERE id_user = 1";
$sql = "SELECT * FROM administrator WHERE id_user=?";
//set up a prepared statement
if ($stmt = $conn->prepare($sql)) {
    //pass parameters
    $stmt->bind_param("i", $_SESSION["id_user"]);
    if ($stmt->errno) {
        displayMessage("stmt prepare( ) had error.", "red");
    }

    // execute
    $stmt->execute();
    if ($stmt->errno) {
        displayMessage("Could not execute prepared statement", "red");
    }

    $stmt->store_result();
    $rowCount = $stmt->num_rows;

    $stmt->bind_result($id_user, $adminName, $adminEmail, $adminPassword);
    $stmt->fetch();

    // Free results
    $stmt->free_result();

    // Close the statement
    $stmt->close();
} // end if( prepare( ))

// Create an associative array mirroring the record in the HTML table
// This will be used to populate the text boxes with the current supplier info
$thisAdmin = [
    "id_user" => $id_user,
    "adminName" => $adminName,
    "adminEmail" => $adminEmail,
    "adminPassword" => $adminPassword,

];
$_SESSION['sessionThisAdmin'] = urlencode(serialize($thisAdmin));

// Is this a return visit?
if (array_key_exists('hidIsReturning', $_POST)) {
    $thisAdmin = unserialize(urldecode($_SESSION['sessionThisAdmin']));

    if ($_POST['btnSubmit'] == 'update') {
        $adminName = mysqli_escape_string($conn, $_POST['txtAName']);
        $adminEmail = mysqli_escape_string($conn, $_POST['txtAEmail']);
        $adminPassword = mysqli_escape_string($conn, $_POST['txtAPassword']);

        if (isset($_POST['txtAName'])) {
            if ($_POST["txtAPassword"] != '') {
                $query = "UPDATE adminstrator SET adminName= $adminName, adminEmail= $adminEmail WHERE id_user=" . $thisAdmin['id_user'];
                $result = $conn->query($query);
                // if ($stmt = $conn->prepare($query)) {
                //     // Bind the parameters
                //     $stmt->bind_param("ssi", $adminName, $adminEmail, $thisAdmin['id_user']);
                //     if ($stmt->errno) {
                //         displayMessage("stmt prepare( ) had error.", "red");
                //     }

                //     // Execute the query
                //     $stmt->execute();
                //     if ($stmt->errno) {
                //         displayMessage("Could not execute prepared statement", "red");
                //     }

                //     $result = $stmt->fetch();

                //     // Free results
                //     $stmt->free_result();

                //     // Close the statement
                //     $stmt->close();
                // }
                if (isset($result)) {
                    echo '<div class="alert alert-success alert-dismissible">Profile Edited</div>';
                }
            } else {
                $query = "UPDATE adminstrator SET adminName= $adminName, adminEmail= $adminEmail, adminPassword=$adminPassword WHERE id_user=" . $thisAdmin['id_user'];
                $result = $conn->query($query);
                // if ($stmt = $conn->prepare($query)) {
                //     // Bind the parameters
                //     $stmt->bind_param("sssi", $adminName, $adminEmail, $adminPassowrd, $thisAdmin['id_user']);
                //     if ($stmt->errno) {
                //         displayMessage("stmt prepare( ) had error.", "red");
                //     }

                //     // Execute the query
                //     $stmt->execute();
                //     if ($stmt->errno) {
                //         displayMessage("Could not execute prepared statement", "red");
                //     }

                //     $result = $stmt->fetch();

                //     // Free results
                //     $stmt->free_result();

                //     // Close the statement
                //     $stmt->close();
                // }
                if (isset($result)) {
                    echo '<div class="alert alert-success alert-dismissible">Profile Edited</div>';
                }
            }
        }
    }
    $_SESSION['sessionThisAdmin'] = urlencode(serialize($thisAdmin));
}


include('header.php');

?>

<div class="panel panel-default">
    <div class="panel-heading">Edit Profile</div>
    <div class="panel-body">
        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" name="frmAdmin" id="edit_profile_form">

            <!-- <form method="post" id="edit_profile_form"> -->
            <span id="message"></span>
            <div class="form-group">
                <label>Name</label>
                <input type="text" name="txtAName" id="txtAName" class="form-control" value="<?php echo $thisAdmin['adminName']; ?>" required />
            </div>
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="txtAEmail" id="txtAEmail" class="form-control" required value="<?php echo $thisAdmin['adminEmail']; ?>" />
            </div>
            <hr />
            <label>Leave Password blank if you do not want to change</label>
            <div class="form-group">
                <label>New Password</label>
                <input type="password" name="txtAPassword" id="txtAPassword" class="form-control" />
            </div>
            <div class="form-group">
                <label>Re-enter Password</label>
                <input type="password" name="txtAPasswordAgain" id="txtAPasswordAgain" class="form-control" />
                <span id="error_password"></span>
            </div>
            <div class="form-group">
                <!-- Use a hidden field to tell server if return visitor -->
                <input type="hidden" name="hidIsReturning" value="true" />
                <input type="submit" name="btnSubmit" id="btnSubmit" value="update" class="btn btn-info" />
            </div>

        </form>
    </div>
</div>
<!-- </form> -->

<!-- <script>
    $(document).ready(function() {
        $('#edit_profile_form').on('submit', function(event) {
            event.preventDefault();
            if ($('#txtAPassword').val() != '') {
                if ($('#txtAPassword').val() != $('#txtAPasswordAgain').val()) {
                    $('#error_password').html('<label class="text-danger">Password Not Match</label>');
                    // $('#btnSubmit').attr('disabled', 'disabled');
                    return false;
                } else {
                    $('#error_password').html('');
                }
            }
            // $('#btnSubmit').attr('disabled', 'disabled');

            var form_data = $(this).serialize();
            $('#txtAPasswordAgain').attr('required', false);
        });
    });
</script> -->

<?php
include('footer.php');
?>