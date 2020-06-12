<?PHP
/* inventoryManagementLib.php - library of common PHP functions 
   used with the Inventory Mgmt System.
   Author: Zulkifl Mohammed
   Written: 12/15/19
*/

/* = = = = = = = = = = = = = = = = = = = 
   Functions are in alphabetical order
 = = = = = = = = = = = = = = = = = = = = */

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * clearThisDepartment( ) - Clear the array $thisDepartment
 * to automatically clear the text boxes.
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function clearThisDepartment()
{
   global $thisDepartment;
   $thisDepartment['department_id'] = "";
   $thisDepartment['departmentName']  = "";
   $thisDepartment['departmentManager']  = "";
} // end of clearThisDepartment( )
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * clearThisProduct( ) - Clear the array $thisProduct
 * to automatically clear the text boxes.
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function clearThisProduct()
{
   global $thisProduct;
   $thisProduct['product_id'] = "";
   $thisProduct['productName']  = "";
   $thisProduct['productDescription']  = "";
   $thisProduct['productPrice']  = "";
   $thisProduct['productQuantity']  = "";
   $thisProduct['departmentName'] = "";
   $thisProduct['supplierName'] = "";
} // end of clearThisProduct( )

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * clearThisSupplier( ) - Clear the array $thisSupplier
 * to automatically clear the text boxes.
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function clearThisSupplier()
{
   global $thisSupplier;
   $thisSupplier['supplier_id'] = "";
   $thisSupplier['supplierName']  = "";
   $thisSupplier['supplierWebsite']  = "";
   $thisSupplier['supplierStatus']  = "";

} // end of clearThisSupplier( )

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * createConnection( ) - Create a database connection
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function createConnection()
{
   global $conn;
   // Create connection object
   $conn = new mysqli(SERVER_NAME, DBF_USER_NAME, DBF_PASSWORD);
   // Check connection
   if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
   }
   // Select the database
   $conn->select_db(DATABASE_NAME);
} // end of createConnection( )

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * displayMessage( ) - Display message to user
 *    Parameters:  $msg -   Text of the message
 *                 $color - Hex color code for text as String
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function displayMessage($msg, $color)
{
   echo "<hr /><strong style='color:" . $color . ";'>" . $msg . "</strong><hr />";
}


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * displayResult( ) - Execute a query and display the result
 *    Parameters:  $rs -  result set to display as 2D array
 *                 $sql - SQL string used to display an error msg
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function displayResult($result, $sql)
{
   if ($result->num_rows > 0) {
      echo "<table border='1'>\n";
      // print headings (field names)
      $heading = $result->fetch_assoc();
      echo "<tr>\n";
      // print field names 
      foreach ($heading as $key => $value) {
         echo "<th>" . $key . "</th>\n";
      }
      echo "</tr>\n";

      // Print values for the first row
      echo "<tr>\n";
      foreach ($heading as $key => $value) {
         echo "<td>" . $value . "</td>\n";
      }

      // output rest of the records
      while ($row = $result->fetch_assoc()) {
         //print_r($row);
         //echo "<br />";
         echo "<tr>\n";
         // print data
         foreach ($row as $key => $value) {
            echo "<td>" . $value . "</td>\n";
         }
         echo "</tr>\n";
      }
      echo "</table>\n";
   } else {
      echo "<strong>zero results using SQL: </strong>" . $sql;
   }
} // end of displayResult( )

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * displayDepartmentTable( ) - Display the department table
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function displayDepartmentTable()
{
   global $conn;
   $sql = "SELECT department.departmentName AS 'Name', department.departmentManager AS 'Manager'
            FROM department";
   $result = $conn->query($sql);
   displayResult($result, $sql);
} // end of displayDepartmentTable()


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * displayProductTable( ) - Display the product table with department and supplier names
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function displayProductTable()
{
   global $conn;
   $sql = "SELECT product.product_id AS 'ID', product.productName AS 'Product Name',
                  product.productQuantity AS 'Quantity', product.productPrice AS 'Price',
                  department.departmentName AS 'Department',
                  supplier.supplierName AS 'Supplier'
            FROM product
            JOIN department
            ON   product.department_id=department.department_id
            JOIN supplier
            ON   product.supplier_id=supplier.supplier_id";
   $result = $conn->query($sql);
   displayResult($result, $sql);
} // end of displayProductTable()


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * displaySupplierTable( ) - Display the Supplier table
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function displaySupplierTable()
{
   global $conn;
   $sql = "SELECT supplier.supplierName AS 'Name', supplier.supplierWebsite AS 'Website', supplier.supplierStatus AS 'Status'
            FROM supplier";
   $result = $conn->query($sql);
   displayResult($result, $sql);
} // end of displaySupplierTable()

/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * formatPhone( ) - Reformat phone 123-456-0000
 * Parameter:  $phoneNumber String - 10 character containing numbers
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function formatPhone($phoneNumber)
{ }


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * runQuery( ) - Execute a query and display message
 * Parameters:  $sql - SQL String to be executed.
 *              $msg - Text of message to display on success or error
 *              $echoSuccess - boolean True=Display message on success
 * If $echoSuccess true: $msg successful. * Error Msg Format: $msg using SQL: $sql.
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
function runQuery($sql, $msg, $echoSuccess)
{
   global $conn;

   // run the query
   if ($conn->query($sql) === TRUE) {
      if ($echoSuccess) {
         echo $msg . " successful.<br />";
      }
   } else {
      echo "<strong>Error when: " . $msg . "</strong> using SQL: " . $sql . "<br />" . $conn->error;
   }
} // end of runQuery( ) 


function countTotalUser($connect)
{
   $sql = "SELECT * FROM administrator";
   if ($stmt = $connect->prepare($sql)) {

      // $stmt->bind_param("ssdi", $productName, $productDescription, $productPrice, $productQuantity);
      // if ($stmt->errno) {
      //     displayMessage("stmt prepare( ) had error.", "red");
      // }

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
   }
   return $totalCount;
}

function countTotalDepartment($connect)
{
   $sql = "SELECT * FROM department";
   if ($stmt = $connect->prepare($sql)) {

      // $stmt->bind_param("ssdi", $productName, $productDescription, $productPrice, $productQuantity);
      // if ($stmt->errno) {
      //     displayMessage("stmt prepare( ) had error.", "red");
      // }

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
   }
   return $totalCount;
}
function countTotalSupplier($connect)
{
   $sql = "SELECT * FROM supplier";
   if ($stmt = $connect->prepare($sql)) {

      // $stmt->bind_param("ssdi", $productName, $productDescription, $productPrice, $productQuantity);
      // if ($stmt->errno) {
      //     displayMessage("stmt prepare( ) had error.", "red");
      // }

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
   }
   return $totalCount;
}

function countTotalProduct($connect)
{
   $sql = "SELECT * FROM product";
   if ($stmt = $connect->prepare($sql)) {

      // $stmt->bind_param("ssdi", $productName, $productDescription, $productPrice, $productQuantity);
      // if ($stmt->errno) {
      //     displayMessage("stmt prepare( ) had error.", "red");
      // }

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
   }
   return $totalCount;
}
