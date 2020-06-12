<!-- createDatabase.php - create a new database with tables 
        and populate it with sample Product data
    Course: CSC235- prjFinal
    Written by: Zulkifl Mohammed
    Email: mohammez@csp.edu
    Written:    12/15/19
    Revised:   
    -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <link rel="stylesheet" href="style.css">
    <title>Create Inventory Database</title>

</head>

<body>
    <h1>Product Inventory Database</h1>
    <?PHP
    // Set up connection constants

    // Using default username and password for AMPPS  
    define("SERVER_NAME", "localhost");
    define("DBF_USER_NAME", "root");
    define("DBF_PASSWORD", "mysql");
    define("DATABASE_NAME", "inventoryManagementSystem");

    // // Credentials for server 
    // define("SERVER_NAME", "sql303.byethost.com");
    // define("DBF_USER_NAME", "b7_24710329");
    // define("DBF_PASSWORD", "A0924869800@a");
    // define("DATABASE_NAME", "b7_24710329_inventoryManagementSystem");


    // Create connection object
    $conn = new mysqli(SERVER_NAME, DBF_USER_NAME, DBF_PASSWORD);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create database if it doesn't exist
    // $sql = "CREATE DATABASE IF NOT EXISTS " . DATABASE_NAME;
    // runQuery($sql, "Creating " . DATABASE_NAME, false);


    // Select the database
    $conn->select_db(DATABASE_NAME);

    /*******************************
     * Create the tables
     *******************************/
    // Create Table:product
    $sql = "CREATE TABLE IF NOT EXISTS product (
    product_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    productName VARCHAR(50) NOT NULL,
    productDescription VARCHAR(20),
    productPrice FLOAT(5,2) NOT NULL,
    productQuantity INT NOT NULL,
    department_id INT,
    supplier_id INT,
    id_user INT 
    )";

    runQuery($sql, "Table: product", false);

    // Create Table:department
    $sql = "CREATE TABLE IF NOT EXISTS department (
        department_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        departmentName VARCHAR(40) NOT NULL,
        departmentManager VARCHAR(40) NOT NULL
        )";
    runQuery($sql, "Table: department", false);

    // Create Table:supplier
    $sql = "CREATE TABLE IF NOT EXISTS supplier (
        supplier_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
        supplierName VARCHAR(50) NOT NULL,
        supplierWebsite VARCHAR(40) NOT NULL,
        supplierStatus VARCHAR(20) NOT NULL
        )";

    runQuery($sql, "Table: supplier", false);

    // Create table: admin
    $sql = "CREATE TABLE IF NOT EXISTS administrator (
        id_user INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        adminName VARCHAR(50) NOT NULL,
        adminEmail VARCHAR(50) NOT NULL,
        adminPassword VARCHAR(50) NOT NULL
    )";
    runQuery($sql, "Table: admin", false);
    /***************************************************
     * Populate Tables Using Sample Data
     * This data will later be collected using a form.
     ***************************************************/


    // Populate Table:product
    $productArray = array(
        array('Bath towel', 'Black', 5.75, 75, 1, 1, 1),
        array('Wash cloth', 'White', 0.99, 225, 1, 1, 1),
        array('Shower curtain', 'White', 11.99, 73, 1, 2, 1),
        array('Pantry organizer', 'Clear', 3.99, 52, 2, 2, 1),
        array('Storage jar', 'Clear', 5.99, 18, 2, 2, 1),
        array('Firm pillow', 'White', 12.99, 24, 3, 1, 1),
        array('Comforter', 'White', 34.99, 12, 3, 3, 1),
        array('Rollaway bed', 'Black', 249.99, 3, 3, 3, 1)
    );

    foreach ($productArray as $product) {
        $sql = "INSERT INTO product (product_id, productName, productDescription, productPrice, productQuantity, department_id, supplier_id, id_user) "
            . "VALUES (NULL, 
        '" . $product[0] . "',
        '" . $product[1] . "',
        '" . $product[2] . "',
        '" . $product[3] . "',
        '" . $product[4] . "',
        '" . $product[5] . "',
        '" . $product[6] . "')";

        runQuery($sql, "New record insert $product[0]", false);
    }


    // Populate Table:department
    $departmentArray = array(
        array("Bath",  "Michael Howard"),
        array("Kitchen", "John Fritz"),
        array("Bedroom", "Liz Tabor")
    );

    foreach ($departmentArray as $department) {
        $sql = "INSERT INTO department (department_id, departmentName, departmentManager) "
            . "VALUES (NULL, '" . $department[0] . "', '"
            . $department[1] . "')";

        //echo "\$sql string is: " . $sql . "<br />";
        runQuery($sql, "New record insert $department[0]", false);
    }
    // Populate Table:sponsor
    $supplierArray = array(
        array("Cannon",  "http://www.cannonhome.com/", 'active'),
        array("InterDesign", "http://www.interdesignusa.com/", 'active'),
        array("LinenSpa", "https://www.linenspa.com/", 'active')
    );

    foreach ($supplierArray as $supplier) {
        $sql = "INSERT INTO supplier (supplier_id, supplierName, supplierWebsite, supplierStatus) "
            . "VALUES (NULL, '" . $supplier[0] . "',
            '" . $supplier[1] . "',
             '" . $supplier[2] . "')";

        //echo "\$sql string is: " . $sql . "<br />";
        runQuery($sql, "New record insert $supplier[0]", false);
    }

    //Populate Table: admin
    $sql = "INSERT INTO admin(id_user, adminName, adminEmail, adminPassword) VALUES(NULL, 'admin', 'admin@email.com', 'admin')";
    runQuery($sql, "New record insert admin ", false);


    // Close the database
    $conn->close();




    /********************************************
     * runQuery( ) - Execute a query and display message
     *    Parameters:  $sql         -  SQL String to be executed.
     *                 $msg         -  Text of message to display on success or error
     *     ___$msg___ successful.    Error when: __$msg_____ using SQL: ___$sql____.
     *                 $echoSuccess - boolean True=Display message on success
     ********************************************/
    function runQuery($sql, $msg, $echoSuccess)
    {
        global $conn;

        //run the query
        if ($conn->query($sql) === TRUE) {
            if ($echoSuccess) {
                echo $msg . " successful.<br />";
            }
        } else {
            echo "<strong>Error when: " . $msg . "</strong> using SQL: " . $sql . "<br />" . $conn->error;
        }
    } // end of runQuery( ) 
    ?>

</body>

</html>