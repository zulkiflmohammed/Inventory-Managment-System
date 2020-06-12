<!-- logOut.php - logout page for the website
    Course: CSC235- prjFinal
    Written by: Zulkifl Mohammed
    Email: mohammez@csp.edu
    Written:    12/15/19
    Revised:   
    -->

<?php
//logout.php
session_start();

session_destroy();

header("location:login.php");

?>
