<!-- databaseConnection.php - create a database connection
    Course: CSC235- prjFinal
    Written by: Zulkifl Mohammed
    Email: mohammez@csp.edu
    Written:    12/15/19
    Revised:   
    -->

<?php
// Using default username and password for AMPPS  
define("SERVER_NAME", "localhost");
define("DBF_USER_NAME", "root");
define("DBF_PASSWORD", "mysql");
define("DATABASE_NAME", "inventoryManagementSystem");

// define("SERVER_NAME", "sql303.byethost.com");
// define("DBF_USER_NAME", "b7_24710329");
// define("DBF_PASSWORD", "A0924869800@a");
// define("DATABASE_NAME", "b7_24710329_inventoryManagementSystem");

    $conn = new mysqli(SERVER_NAME, DBF_USER_NAME, DBF_PASSWORD);
   // Check connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   } 
   // Select the database
   $conn->select_db(DATABASE_NAME);

   session_start();

   ?>
