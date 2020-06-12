<!-- department.php - CRUD department info
    Course: CSC235- prjFinal
    Written by: Zulkifl Mohammed
    Email: mohammez@csp.edu
    Written:    12/15/19
    Revised:   
    -->

<?PHP session_start();
ini_set('display_errors', E_ALL);

if (!isset($_SESSION["id_user"])) {
    header("location:logIn.php");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- custom -->
    <!-- <link rel="stylesheet" href="styler.css"> -->

    <title>Inventory Management System</title>

    <?PHP
    // Using default username and password for AMPPS  
    define("SERVER_NAME", "localhost");
    define("DBF_USER_NAME", "root");
    define("DBF_PASSWORD", "mysql");
    define("DATABASE_NAME", "inventoryManagementSystem");

    // define("SERVER_NAME", "sql303.byethost.com");
    // define("DBF_USER_NAME", "b7_24710329");
    // define("DBF_PASSWORD", "A0924869800@a");
    // define("DATABASE_NAME", "b7_24710329_inventoryManagementSystem");


    // include('databaseConnection.php');
    // include('inventoryManagementLib.php');
    // Global connection object
    $conn = NULL;

    // Link to external library file
    require_once(getcwd() . "\inventoryManagementLib.php");

    // Connect to database
    createConnection();



    // Is this a return visit?
    if (array_key_exists('hidIsReturning', $_POST)) {
        $thisDepartment = unserialize(urldecode($_SESSION['sessionThisDepartment']));


        if (isset($_POST['lstDepartment']) && !($_POST['lstDepartment'] == 'new')) {

            $idDepartment = $_POST['lstDepartment'];
            $sql = "SELECT department.department_id, departmentName, departmentManager FROM department WHERE department.department_id=?";

            //set up a prepared statement
            if ($stmt = $conn->prepare($sql)) {
                //pass parameters
                $stmt->bind_param("i", $idDepartment);
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

                $stmt->bind_result($idDepartment, $departmentName, $departmentManager);
                $stmt->fetch();

                // Free results
                $stmt->free_result();

                // Close the statement
                $stmt->close();
            } // end if( prepare( ))

            // Create an associative array mirroring the record in the HTML table
            // This will be used to populate the text boxes with the current department info
            $thisDepartment = [
                "department_id" => $idDepartment,
                "departmentName" => $departmentName,
                "departmentManager" => $departmentManager
            ];

            // print_r($thisDepartment);

            $_SESSION['sessionThisDepartment'] = urlencode(serialize($thisDepartment));
        } // end if lstDepartment        



        // Determine which button may have been clicked
        switch ($_POST['btnSubmit']) {
                // = = = = = = = = = = = = = = = = = = = 
                // DELETE  
                // = = = = = = = = = = = = = = = = = = = 
            case 'delete':

                // //Make sure a department has been selected.
                if ($_POST["txtDName"] == "") {
                    displayMessage("Please select a department's name.", "red");
                } else {
                    // Remove any records in Table:department
                    $sql = "DELETE FROM department WHERE department_id =?";
                    // Prepare
                    if ($stmt = $conn->prepare($sql)) {
                        // Bind the parameters
                        $stmt->bind_param("i", $thisDepartment['department_id']);
                        if ($stmt->errno) {
                            displayMessage("stmt prepare( ) had error.", "red");
                        }

                        // Execute the query
                        $stmt->execute();
                        if ($stmt->errno) {
                            displayMessage("Could not execute prepared statement", "red");
                        }

                        // Free results
                        $stmt->free_result();

                        // Close the statement
                        $stmt->close();
                    }



                    if ($result) {
                        displayMessage($thisDepartment['departmentName'] . " deleted.", "green");
                    }
                }
                // Zero out the current selected department
                clearThisDepartment();
                break;

                // = = = = = = = = = = = = = = = = = = = 
                // ADD NEW DEPARTMENT 
                // = = = = = = = = = = = = = = = = = = = 
            case 'new':
                $departmentName = $_POST['txtDName'];
                $departmentManager = $_POST['txtDManager'];


                $sql = "SELECT departmentName, departmentManager FROM department 
                WHERE departmentName=? AND   departmentManager=?";

                // Set up a prepared statement
                if ($stmt = $conn->prepare($sql)) {

                    $stmt->bind_param("ss", $departmentName, $departmentManager);
                    if ($stmt->errno) {
                        displayMessage("stmt prepare( ) had error.", "red");
                    }

                    // Execute the query
                    $stmt->execute();
                    if ($stmt->errno) {
                        displayMessage("Could not execute prepared statement", "red");
                    }

                    // Store the result
                    $stmt->store_result();
                    $totalCount = $stmt->num_rows;

                    // Free results
                    $stmt->free_result();
                    // Close the statement
                    $stmt->close();
                } // end if( prepare( ))


                // Supplier already registered?
                if ($totalCount > 0) {
                    displayMessage("This department is already registered.", "red");
                }
                //No duplicates
                else {
                    // Check for empty suppler name description price quantity fields 
                    if (
                        $_POST['txtDName'] == ""
                        || $_POST['txtDManager'] == ""
                    ) {
                        displayMessage("Not Saved! Please type in department information.", "red");
                    }
                    // supplier info are populated
                    else {

                        $sql = "INSERT INTO department (department_id, departmentName, departmentManager)
                                VALUES(NULL, ?,?)";
                        // Set up a prepared statement
                        if ($stmt = $conn->prepare($sql)) {
                            // Pass the parameters
                            $stmt->bind_param("ss", $departmentName, $departmentManager);
                            if ($stmt->errno) {
                                displayMessage("stmt prepare( ) had error.", "red");
                            }

                            // Execute the query
                            $stmt->execute();
                            if ($stmt->errno) {
                                displayMessage("Could not execute prepared statement", "red");
                            }

                            // Store the result
                            $stmt->store_result();
                            $totalCount = $stmt->num_rows;

                            // Free results
                            $stmt->free_result();
                            // Close the statement
                            $stmt->close();
                        } // end if( prepare( ))


                    } // end of if/else empty department name manager fields
                    // Zero out the current selected deprtment
                    clearThisDepartment();
                } // end of if/else($total > 0)
                break;

                // = = = = = = = = = = = = = = = = = = = 
                // UPDATE   
                // = = = = = = = = = = = = = = = = = = = 
            case 'update':


                // Check for empty name 
                if ($_POST['txtDName'] == "") {
                    displayMessage("Please select a department's name.", "red");
                }
                // department name is selected
                else {
                    $isSuccessful = false;
                    // Update Table:department

                    $departmentName = mysqli_real_escape_string($conn, $_POST['txtDName']);
                    $departmentManager = mysqli_real_escape_string($conn, $_POST['txtDManager']);


                    $sql = "UPDATE department SET departmentName='$departmentName', departmentManager='$departmentManager' WHERE department_id = " . $thisDepartment['department_id'];
                    $result = $conn->query($sql);
                    if ($result) {
                        $isSuccessful = true;
                    }

                    // If successful update the variables
                    if ($isSuccessful) {
                        displayMessage("Update successful!", "green");
                        $thisDepartment['department_id'] = $_POST['department_id'];
                        $thisDepartment['departmentName'] = $_POST['txtDName'];
                        $thisDepartment['departmentName'] = $_POST['txtDManager'];


                        // Save array as a serialized session variable
                        $_SESSION['sessionThisDepartment'] = urlencode(serialize($thisDepartment));
                    }
                }

                clearThisDepartment();
                break;
        } // end of switch( )


    } else // or, a first time visitor?
    {
        // echo '<h1>Welcome FIRST TIME</h1>';
    } // end of if new else returning
    ?>

</head>

<body>


    <nav class="navbar navbar-default">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a href="index.php" class="navbar-brand"><img src="graphic/myLogo.png" height="50" alt="Company Logo" style="margin-top: -7px;"></a>
            </div>

            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <div>
                    <ul class="nav navbar-nav">
                        <li><a href="reflection.html" target="_blank"><button type="button" class="btn btn-primary">Reflection</button></a></li>

                    </ul>
                    <ul class="nav navbar-nav navbar-right">
                        <li><a href="readMe.html" target="_blank"><button type="button" class="btn btn-default">Read Me</button></a></li>

                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- <a class="navbar-brand" href="index.php">
        <img src="graphic/myLogo.png" height="60" alt="Company Logo">
    </a> -->
    <br />
    <div class="container">
        <h2 align="center">Inventory Management System</h2>

        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-2" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a href="index.php" class="navbar-brand">Home</a>
                </div>

                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-2">
                    <div>
                        <ul class="nav navbar-nav">
                            <li><a href="product.php">Product</a></li>
                            <li><a href="department.php">Department</a></li>
                            <li><a href="supplier.php">Suppliers</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count"></span> <?php echo $_SESSION["user_name"]; ?>Admin</a>
                                <ul class="dropdown-menu">
                                    <li><a href="profile.php">Profile</a></li>
                                    <li><a href="logout.php">Logout</a></li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </div>

            </div>
        </nav>

        <div class="content">
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" name="frmDepartment" id="frmDepartment" class="form-row">

                <label for="lstDepartment"><strong>Select Department's Name</strong></label>

                <select name="lstDepartment" id="lstDepartment" onChange="this.form.submit();">
                    <option value="new">Select a name</option>
                    <?PHP
                    // Loop through the department table to build the <option> list
                    $sql = "SELECT department.department_id, departmentName
                    FROM department";
                    $result = $conn->query($sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['department_id'] . "'>" . $row['departmentName'] . "</option>\n";
                    }


                    ?>
                </select>
                <br />
                <br />

                <fieldset class="form-horizontal form-inline">
                    <legend>Department's Information</legend>


                    <div class="form-group col-sm-4">
                        <label for="txtDName">Department Name</label>
                        <input type="text" class="form-control" name="txtDName" id="txtDName" value="<?php echo $thisDepartment['departmentName']; ?>" />
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="txtDManager">Manager</label>
                        <input type="text" class="form-control" name="txtDManager" id="txtDManager" value="<?php echo $thisDepartment['departmentManager']; ?>" />
                    </div>



                </fieldset>

                <br />
                <button name="btnSubmit" value="delete" class="btn btn-danger" style="float:left;" onclick="this.form.submit();">
                    Delete
                </button>

                <button name="btnSubmit" value="update" class="btn btn-warning" style="float:right;" onclick="this.form.submit();">
                    Update
                </button>
                <br />
                <!-- Use a hidden field to tell server if return visitor -->
                <input type="hidden" name="hidIsReturning" value="true" />
            </form>

            <!-- Button trigger modal -->
            <!-- <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" style="text-align: center;">
                Add New Department Information
            </button> -->
            <br />
            <br />


            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                                    <h3 class="panel-title">Department List</h3>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align='right'>
                                    <button type="button" name="add" id="add_button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#exampleModal">Add New Department</button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 table-responsive">
                                    <table id="department_data" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Department Name</th>
                                                <th>Manager</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Loop through the department table to build the <option> list
                                            $sql = "SELECT department.department_id, departmentName, departmentManager
                                                FROM department";

                                            $result = $conn->query($sql);
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                foreach ($row as $key => $value) {
                                                    echo "<td>" . $value . "</td>\n";
                                                }

                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Add New Department Information</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" name="frmDepartment" id="frmDepartment">
                            <div class="modal-body">

                                <div class="form-group">
                                    <label for="txtDName">Department Name</label>
                                    <input type="text" name="txtDName" id="txtDName" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="txtDManager">Manager</label>
                                    <input type="text" name="txtDManager" id="txtDManager" class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <!-- Use a hidden field to tell server if return visitor -->
                                <input type="hidden" name="hidIsReturning" value="true" />
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <button name="btnSubmit" value="new" type="submit" class="btn btn-primary">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Footer -->
    <footer class="page-footer font-small blue">

        <!-- Copyright -->
        <div class="footer-copyright text-center py-3">© 2019 Copyright:
            <a href="https://zulkifl.com/"> Zulkifl Mohammed</a>
        </div>
        <!-- Copyright -->

    </footer>
    <!-- Footer -->


    <!-- jQuery first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-2.1.4.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>

</html>