<!-- logIn.php - login page for the website
    Course: CSC235- prjFinal
    Written by: Zulkifl Mohammed
    Email: mohammez@csp.edu
    Written:    12/15/19
    Revised:   
    -->

<?php
//login.php

include('databaseConnection.php');
include('inventoryManagementLib.php');

if (isset($_SESSION['id_user'])) {
    header("Location: index.php");
}

$message = '';

if (isset($_POST["login"])) {
    $sql = "SELECT * FROM administrator WHERE adminEmail=?";
    //set up a prepared statement
    if ($stmt = $conn->prepare($sql)) {
        //pass parameters
        $stmt->bind_param("s", $_POST["user_email"]);
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

    if ($rowCount > 0) {
        if ($_POST["user_password"]==$adminPassword) {
            $_SESSION['id_user'] = $id_user;
            $_SESSION['adminName'] = $adminName;
            header("Location: index.php");
        } else {
            $message = "<label>Wrong Password</label>";
        }
    } else {
        $message = "<label>Wrong Email Address</labe>";
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Inventory Management System</title>
    <!-- jQuery first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-2.1.4.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
</head>

<body>
    <br />
    <div class="container" style="max-width: 500px; margin: auto;">
        <h2 align="center">Inventory Management System</h2>
        <img src="graphic/myLogo.png" alt="Company Logo" style="border-radius: 50%; display: block; margin: auto; width: 50%;">
        <br />
        <div class="panel panel-default">
            <div class="panel-heading">Login</div>
            <div class="panel-body">
                <form method="post">
                    <?php echo $message; ?>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" name="user_email" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="user_password" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <input type="submit" name="login" value="Login" class="btn btn-success" />
                    </div>

                    <div>
                        <label for="">Email: admin@email.com  Pass: admin</label>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
</body>

</html>