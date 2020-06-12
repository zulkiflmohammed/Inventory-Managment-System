<?php
include('databaseConnection.php');

if (isset($_POST['product'])) {

    if ($_POST['product'] == 'delete') {
        $sql = "DELETE FROM product WHERE product_id = :product_id";
        $result = $conn->query($sql);
    }
}

?>
