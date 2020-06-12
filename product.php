<!-- product.php - CRUD product info
    Course: CSC235- prjFinal
    Written by: Zulkifl Mohammed
    Email: mohammez@csp.edu
    Written:    12/15/19
    Revised:   
    -->

<?PHP 

session_start();

if(!isset($_SESSION["id_user"]))
{
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
        $thisProduct = unserialize(urldecode($_SESSION['sessionThisProduct']));


        if (isset($_POST['lstProduct']) && !($_POST['lstProduct'] == 'new')) {

            $idProduct = $_POST['lstProduct'];
            $sql = "SELECT product.product_id, productName, productDescription, productPrice, productQuantity, departmentName, supplierName
            FROM product
            JOIN department
            ON   product.department_id=department.department_id
            JOIN supplier
            ON   product.supplier_id=supplier.supplier_id
            WHERE product.product_id=?";

            //set up a prepared statement
            if ($stmt = $conn->prepare($sql)) {
                //pass parameters
                $stmt->bind_param("i", $idProduct);
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

                $stmt->bind_result($idProduct, $productName, $productDescription, $productPrice, $productQuantity, $departmentName, $supplierName);
                $stmt->fetch();

                // Free results
                $stmt->free_result();

                // Close the statement
                $stmt->close();
            } // end if( prepare( ))

            // Create an associative array mirroring the record in the HTML table
            // This will be used to populate the text boxes with the current product info
            $thisProduct = [
                "product_id" => $idProduct,
                "productName" => $productName,
                "productDescription" => $productDescription,
                "productPrice" => $productPrice,
                "productQuantity" => $productQuantity,
                "departmentName" => $departmentName,
                "supplierName" => $supplierName
            ];


            $_SESSION['sessionThisProduct'] = urlencode(serialize($thisProduct));
        } // end if lstProduct        



        // Determine which button may have been clicked
        switch ($_POST['btnSubmit']) {
                // = = = = = = = = = = = = = = = = = = = 
                // DELETE  
                // = = = = = = = = = = = = = = = = = = = 
            case 'delete':

                // //Make sure a product has been selected.
                if ($_POST["txtPName"] == "") {
                    displayMessage("Please select a product's name.", "red");
                } else {
                    // Remove any records in Table:product
                    $sql = "DELETE FROM product WHERE product_id =?";
                    // Prepare
                    if ($stmt = $conn->prepare($sql)) {
                        // Bind the parameters
                        $stmt->bind_param("i", $thisProduct['product_id']);
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
                        displayMessage($thisProduct['productName'] . " deleted.", "green");
                    }
                }
                // Zero out the current selected product
                clearThisProduct();
                break;

                // = = = = = = = = = = = = = = = = = = = 
                // ADD NEW PRODUCT 
                // = = = = = = = = = = = = = = = = = = = 
            case 'new':
                $productName = $_POST['txtPName'];
                $productDescription = $_POST['txtPDescription'];
                $productPrice = $_POST['txtPPrice'];
                $productQuantity = $_POST['txtPQuantity'];
                $department_id = $_POST['lstDepartment'];
                $supplier_id = $_POST['lstSupplier'];
                $id_user = 1;


                $sql = "SELECT productName, productDescription, productPrice, productQuantity FROM product 
                WHERE productName=? AND   productDescription=? AND   productPrice=? AND   productQuantity=?";

                // Set up a prepared statement
                if ($stmt = $conn->prepare($sql)) {

                    $stmt->bind_param("ssdi", $productName, $productDescription, $productPrice, $productQuantity);
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


                // Product already registered?
                if ($totalCount > 0) {
                    displayMessage("This product is already in the inventory.", "red");
                }
                //No duplicates
                else {
                    // Check for empty product name description price quantity fields 
                    if (
                        $_POST['txtPName'] == ""
                        || $_POST['txtPDescription'] == ""
                        || $_POST['txtPPrice'] == ""
                        || $_POST['txtPQuantity'] == ""
                    ) {
                        displayMessage("Please type in product information.", "red");
                    }
                    // Product info are populated
                    else {

                        $sql = "INSERT INTO product (product_id, productName, productDescription, productPrice, productQuantity, department_id, supplier_id, id_user)
                                VALUES(NULL, ?,?,?,?,?,?,?)";
                        // Set up a prepared statement
                        if ($stmt = $conn->prepare($sql)) {
                            // Pass the parameters
                            $stmt->bind_param("ssdiiii", $productName, $productDescription, $productPrice, $productQuantity, $department_id, $supplier_id, $id_user);
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


                    } // end of if/else empty product name description price quantity fields
                    // Zero out the current selected product
                    clearThisProduct();
                } // end of if/else($total > 0)
                break;

                // = = = = = = = = = = = = = = = = = = = 
                // UPDATE   
                // = = = = = = = = = = = = = = = = = = = 
            case 'update':


                // Check for empty name 
                if ($_POST['txtPName'] == "") {
                    displayMessage("Please select a product's name.", "red");
                }
                // product name is selected
                else {
                    $isSuccessful = false;
                    // Update Table:product



                    // Create a new one for the stored procedure
                    mysqli_close($conn);
                    createConnection();
                    // Set up the SQL String, calling a stored procedure
                    $result = mysqli_query($conn, "CALL updateProduct('"
                        . $_POST['txtPName'] . "','" . $_POST['txtPDescription'] . "','"
                        . $_POST['txtPPrice'] . "','" . $_POST['txtPQuantity'] . "', 
                        " . $thisProduct['product_id'] . ")")
                        or die("Query using Stored Procedure failed." . mysqli_error($conn));

                    if ($result) {
                        $isSuccessful = true;
                    }
                    // Close the stored procedure connection and reopen a new one
                    // for other SQL calls
                    mysqli_close($conn);
                    createConnection();



                    // Update Table:department
                    // escape variables for security
                    $departmentName = mysqli_real_escape_string($conn, $_POST['txtPDepartment']);
                    // !!!! Does not update department unless an entry already exists in the table !!!!
                    $sql = "UPDATE department SET departmentName='$departmentName' WHERE product_id = " . $thisProduct['product_id'];
                    $result = $conn->query($sql);
                    if ($result) {
                        $isSuccessful = true;
                    }

                    // Update Table:supplier
                    // escape variables for security
                    $supplierName = mysqli_real_escape_string($conn, $_POST['txtPSupplier']);
                    // !!!! Does not update supplier unless an entry already exists in the table !!!!
                    $sql = "UPDATE supplier SET supplierName='$supplierName' WHERE product_id = " . $thisProduct['product_id'];
                    $result = $conn->query($sql);
                    if ($result) {
                        $isSuccessful = true;
                    }
                    // If successful update the variables
                    if ($isSuccessful) {
                        displayMessage("Update successful!", "green");
                        $thisProduct['product_id'] = $_POST['product_id'];
                        $thisProduct['productName'] = $_POST['txtPName'];
                        $thisProduct['productDescription'] = $_POST['txtPDescription'];
                        $thisProduct['productPrice'] = $_POST['txtPPrice'];
                        $thisProduct['productQuantity'] = $_POST['txtPQuantity'];
                        $thisProduct['supplierName'] = $_POST['txtPSupplier'];
                        $thisProduct['departmentName'] = $_POST['txtPDepartment'];


                        // Save array as a serialized session variable
                        $_SESSION['sessionThisProduct'] = urlencode(serialize($thisProduct));
                    }
                }

                clearThisProduct();
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
            <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" name="frmProduct" id="frmProduct" class="form-row">

                <label for="lstProduct"><strong>Select Product's Name</strong></label>

                <select name="lstProduct" id="lstProduct" onChange="this.form.submit();">
                    <option value="new">Select a name</option>
                    <?PHP
                    // Loop through the product table to build the <option> list
                    //     $sql = "SELECT product.product_id, productName
                    // FROM product";
                    //     $result = $conn->query($sql);
                    //     while ($row = $result->fetch_assoc()) {
                    //         echo "<option value='" . $row['product_id'] . "'>" . $row['productName'] . "</option>\n";
                    //     }
                    // Close out existing connection
                    // Create a new one for the stored procedure
                    mysqli_close($conn);
                    createConnection();
                    // Set up the SQL String, calling a stored procedure
                    $sql = 'call getProductList()';
                    // Run the stored procedure
                    $result = $conn->query($sql);
                    // Extract out information from the array, storing each item in the dropdown list
                    while ($row = $result->fetch_assoc()) {
                        echo "<option value='" . $row['product_id'] . "'>" . $row['productName'] . "</option>\n";
                    }
                    // Close the stored procedure connection and reopen a new one
                    // for other SQL calls
                    mysqli_close($conn);
                    createConnection();

                    ?>
                </select>
                <br />
                <br />

                <fieldset class="form-horizontal form-inline">
                    <legend>Product's Information</legend>


                    <div class="form-group col-sm-4">
                        <label for="txtPName">Product Name</label>
                        <input type="text" class="form-control" name="txtPName" id="txtPName" value="<?php echo $thisProduct['productName']; ?>" />
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="txtPDescription">Description</label>
                        <input type="text" class="form-control" name="txtPDescription" id="txtPDescription" value="<?php echo $thisProduct['productDescription']; ?>" />
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="txtPPrice">Price</label>
                        <input type="text" class="form-control" name="txtPPrice" id="txtPPice" value="<?php echo $thisProduct['productPrice']; ?>" />
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="txtPQuantity">Quantity</label>
                        <input type="text" class="form-control" name="txtPQuantity" id="txtPQuantity" value="<?php echo $thisProduct['productQuantity']; ?>" />
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="txtPDepartment">Department</label>
                        <input type="text" class="form-control" name="txtPDepartment" id="txtPDepartment" value="<?php echo $thisProduct['departmentName']; ?>" />
                    </div>

                    <div class="form-group col-sm-4">
                        <label for="txtPSupplier">Supplier</label>
                        <input type="text" class="form-control" name="txtPSupplier" id="txtPSupplier" value="<?php echo $thisProduct['supplierName']; ?>" />
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
                Add New Product Information
            </button> -->
            <br />
            <br />


            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                                    <h3 class="panel-title">Product List</h3>
                                </div>

                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6" align='right'>
                                    <button type="button" name="add" id="add_button" class="btn btn-success btn-xs" data-toggle="modal" data-target="#exampleModal">Add New Product</button>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-sm-12 table-responsive">
                                    <table id="product_data" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Product Name</th>
                                                <th>Description</th>
                                                <th>Quantity</th>
                                                <th>Price</th>
                                                <th>Department</th>
                                                <th>Supplier</th>
                                                <!-- <th></th>
                                                    <th></th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Loop through the product table to build the <option> list
                                            $sql = "SELECT product_id, productName, productDescription, productQuantity, productPrice,
                                                        department.departmentName,
                                                        supplier.supplierName
                                                FROM product
                                                JOIN department
                                                ON   product.department_id=department.department_id
                                                JOIN supplier
                                                ON   product.supplier_id=supplier.supplier_id";

                                            $result = $conn->query($sql);
                                            while ($row = $result->fetch_assoc()) {
                                                echo "<tr>";
                                                foreach ($row as $key => $value) {
                                                    echo "<td>" . $value . "</td>\n";
                                                }
                                                // echo '<td><button type="button" name="btnSubmit" value="' . $row['product_id'] . '" id="btnEdit" class="btn btn-warning btn-xs">Edit</button></td>';
                                                // echo '<td><button type="button" name="btnSubmit" value="' . $row['product_id'] . '" id="btnEdit" class="btn btn-danger btn-xs">Delete</button></td>';
                                                echo "</tr>";

                                                // echo "<option value='" . $row['product_id'] . "'>" . $row['productName'] . "</option>\n";
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
                            <h5 class="modal-title" id="exampleModalLabel">Add New Product Information</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="POST" name="frmProduct" id="frmProduct">
                            <div class="modal-body">

                                <div class="form-group">
                                    <label for="txtPName">Product Name</label>
                                    <input type="text" name="txtPName" id="txtPName" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="txtPDescription">Description</label>
                                    <input type="text" name="txtPDescription" id="txtPDescription" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="txtPPrice">Price</label>
                                    <input type="text" name="txtPPrice" id="txtPPrice" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="txtPQuantity">Quantity</label>
                                    <input type="text" name="txtPQuantity" id="txtPQuantity" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="lstDepartment">Department</label>
                                    <select name="lstDepartment" id="lstDepartment">
                                        <option value="department"></option>
                                        <?PHP
                                        // // Loop through the department table to build the <option> list
                                        // $sql = "SELECT department.department_id, departmentName
                                        // FROM department";
                                        // $result = $conn->query($sql);
                                        // while ($row = $result->fetch_assoc()) {
                                        //     echo "<option value='" . $row['department_id'] . "'>" . $row['departmentName'] . "</option>\n";
                                        // }

                                        mysqli_close($conn);
                                        createConnection();
                                        // Set up the SQL String, calling a stored procedure
                                        $sql = 'call getDepartmentList()';
                                        // Run the stored procedure
                                        $result = $conn->query($sql);
                                        // Extract out information from the array, storing each item in the dropdown list
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['department_id'] . "'>" . $row['departmentName'] . "</option>\n";
                                        }
                                        // Close the stored procedure connection and reopen a new one
                                        // for other SQL calls
                                        mysqli_close($conn);
                                        createConnection();
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="lstSupplier">Supplier</label>
                                    <select name="lstSupplier" id="lstSupplier">
                                        <option value="supplier"></option>
                                        <?PHP
                                        // // Loop through the supplier table to build the <option> list
                                        // $sql = "SELECT supplier.supplier_id, supplierName
                                        //     FROM supplier";
                                        // $result = $conn->query($sql);
                                        // while ($row = $result->fetch_assoc()) {
                                        //     echo "<option value='" . $row['supplier_id'] . "'>" . $row['supplierName'] . "</option>\n";
                                        // }

                                        mysqli_close($conn);
                                        createConnection();
                                        // Set up the SQL String, calling a stored procedure
                                        $sql = 'call getSupplierList()';
                                        // Run the stored procedure
                                        $result = $conn->query($sql);
                                        // Extract out information from the array, storing each item in the dropdown list
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['supplier_id'] . "'>" . $row['supplierName'] . "</option>\n";
                                        }
                                        // Close the stored procedure connection and reopen a new one
                                        // for other SQL calls
                                        mysqli_close($conn);
                                        createConnection();
                                        ?>
                                    </select>
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