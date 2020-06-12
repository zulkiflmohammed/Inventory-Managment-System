<!-- index.php - home page for the website: displays general info about the inventory
    Course: CSC235- prjFinal
    Written by: Zulkifl Mohammed
    Email: mohammez@csp.edu
    Written:    12/15/19
    Revised:   
    -->

<?php
//index.php
include('databaseConnection.php');
include('inventoryManagementLib.php');

if(!isset($_SESSION["id_user"]))
{
 header("location:logIn.php");
}

include('header.php');
?>

<br />
<div class="row">
    <?php
    // if ($_SESSION['type'] == 'master') {
    //     ?>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Total Users</strong></div>
                <div class="panel-body" align="center">
                    <h1><?php echo countTotalUser($conn); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Total Departments</strong></div>
                <div class="panel-body" align="center">
                    <h1><?php echo countTotalDepartment($conn); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Total Suppliers</strong></div>
                <div class="panel-body" align="center">
                    <h1><?php echo countTotalSupplier($conn); ?></h1>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Total Items in Stock</strong></div>
                <div class="panel-body" align="center">
                    <h1><?php echo countTotalProduct($conn); ?></h1>
                </div>
            </div>
        </div>
    <?php
    // }
    ?>
    <!-- <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Total Order Value</strong></div>
            <div class="panel-body" align="center">
                <h1>$<?php 
                // echo count_total_order_value($connect); 
                ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Total Cash Order Value</strong></div>
            <div class="panel-body" align="center">
                <h1>$<?php 
                // echo count_total_cash_order_value($connect); 
                ?></h1>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="panel panel-default">
            <div class="panel-heading"><strong>Total Credit Order Value</strong></div>
            <div class="panel-body" align="center">
                <h1>$<?php 
                // echo count_total_credit_order_value($connect); 
                ?></h1>
            </div>
        </div>
    </div>
    <hr /> -->
    <?php
    // if ($_SESSION['type'] == 'master') {
        ?>
        <!-- <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading"><strong>Total Order Value User wise</strong></div>
                <div class="panel-body" align="center">
                    <?php 
                    // echo get_user_wise_total_order($connect); 
                    ?>
                </div>
            </div>
        </div> -->
    <?php
    // }
    ?>

</div>


<?php
include('footer.php');

?>