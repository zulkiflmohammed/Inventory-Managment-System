<?php
//index.php

// The JSON standard MIME header. Output as JSON, not HTML
header('Content-type: application/json');


include('databaseConnection.php');
// include('inventoryManagementLib.php');

// if(!isset($_SESSION["type"]))
// {
//  header("location:login.php");
// }
// Get the product and related data from the tables
// Use the RANDOM function and the $limit variable 
// so only four records at random are extracted.

if (isset($_POST['product'])) {

    $sql = "SELECT product_id, productName, productDescription, productQuantity, productPrice,
                    department.departmentName,
                    supplier.supplierName
            FROM product
            JOIN department
            ON   product.department_id=department.department_id
            JOIN supplier
            ON   product.supplier_id=supplier.supplier_id";

    $result = $conn->query($sql);
    // displayResult($result, $sql);

    // Loop through the $result to create JSON formatted data   
    $productArray = array();
    while ($thisRow = $result->fetch_assoc()) {
        $productArray[] = $thisRow;
    }
    echo json_encode($productArray);

    switch ($_POST['product']){

        case 'delete':
            $sql = "DELETE FROM product WHERE product_id = " . $_POST['product_id'];
            $result = $conn->query($sql);
        break;
        

    }

    // $productArray = array();
    // $stmt = $connect->prepare($sql);
    // $stmt->execute();
    // $result = $stmt->get_result();
    // // Loop through the $result to create JSON formatted data   
    // while ($row = $result->fetch_assoc()) {
    //     $productArray[] = $row;
    // }

    // $stmt->close();


    // if ($stmt = $connect->prepare($sql)) {
    //     // Execute the query
    //     $stmt->execute();
    //     if ($stmt->errno) {
    //         displayMessage("Could not execute prepared statement", "red");
    //     }

    //     // Store the result
    //     // $stmt->store_result();
    //     // $totalCount = $stmt->num_rows;

    //     $result = $stmt->get_result();

    //     // Loop through the $result to create JSON formatted data   
    //     while ($thisRow = $result->fetch_assoc()) {
    //         $productArray[] = $thisRow;
    //     }

    //     $stmt->fetch();

            
    //     // Free results
    //     $stmt->free_result();
    //     // Close the statement
    //     $stmt->close();
    // }



    // echo json_encode($productArray);
}
