<?php
ini_set('display_errors', E_ALL);

//index.php
include('databaseConnection.php');
include('inventoryManagementLib.php');

// if(!isset($_SESSION["type"]))
// {
//  header("location:login.php");
// }

if (array_key_exists('hdnReturning', $_POST)) {
    if (isset($_POST['delete'])) {
        $sql = "DELETE FROM product WHERE product_id =" . $_POST['delete'];
        $result = $conn->query($sql);
    }
}

include('header.php');
?>
<script>
    function showProduct() {
        var productList = document.getElementById("productList");
        var httpReq = new XMLHttpRequest();

        // Add AJAX call
        // Request the API script using POST, calling the PHP script
        httpReq.open("POST", "productCRUD.php", true);
        httpReq.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        httpReq.onreadystatechange = function() {
            if (httpReq.readyState == 4 && httpReq.status == 200) {
                var dataObject = JSON.parse(httpReq.responseText);
                productList.innerHTML = "";

                var stringToDisplay = "";
                // Clear the data each time around
                stringToDisplay += "<div class='row'>";
                stringToDisplay += "<div class='col-lg-12'>";
                stringToDisplay += "<div class='panel panel-default'><div class='panel-heading'>";
                stringToDisplay += "<div class='row'>";
                stringToDisplay += "<div class='col-lg-10 col-md-10 col-sm-8 col-xs-6'>";
                stringToDisplay += "<h3 class='panel-title'>Product List</h3></div>";
                stringToDisplay += "<div class='col-lg-2 col-md-2 col-sm-4 col-xs-6' align='right'>";

                stringToDisplay += "<button type='button' value='add' id='add_button' class='btn btn-success btn-xs'>Add New Product</button></div></div></div>";
                stringToDisplay += "<div class = 'panel-body'><div class ='row'><div class = 'col-sm-12 table-responsive' > ";
                stringToDisplay += "<table id='product_data' class='table table-bordered table-striped'>";
                stringToDisplay += "<thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Quantity</th><th>Department</th><th>Supplier</th><th></th><th></th></tr></thead>";
                for (var index in dataObject) {
                    stringToDisplay += "<tr>";
                    stringToDisplay += "<td>" + dataObject[index].product_id + "</td>";
                    stringToDisplay += "<td>" + dataObject[index].productName + "</td>";
                    stringToDisplay += "<td>" + dataObject[index].productDescription + "</td>";
                    stringToDisplay += "<td>" + dataObject[index].productPrice + "</td>";
                    stringToDisplay += "<td>" + dataObject[index].productQuantity + "</td>";
                    stringToDisplay += "<td>" + dataObject[index].departmentName + "</td>";
                    stringToDisplay += "<td>" + dataObject[index].supplierName + "</td>";
                    stringToDisplay += "<td><button type='button' value='edit' id='btnEdit' class='btn btn-warning btn-xs'>Edit</button></td>"
                    stringToDisplay += "<td><button type='button' name='delete' value='" + dataObject[index].product_id + "' onClick='myFunction()' class='btn btn-danger btn-xs'>Delete</button></td>"
                    stringToDisplay += "</tr>";

                } // end of for( )
                stringToDisplay += "</table><br />";
                stringToDisplay += "</div></div></div></div></div></div>"
                // Add a hidden field
                stringToDisplay += "<input type='hidden' name='hdnReturning' value='returning'  />";
                stringToDisplay += "</form>";
                // stringToDisplay += "</form>";
                // Display the String containing the HTML table output as the text of the #result <div>.
                productList.innerHTML = stringToDisplay;
            } // end of if readyState
        } // end of onreadystatechange

        // Send the request with a POST variable of product
        httpReq.send("product");
        productList.innerHTML = "<br />Requesting data from server...";
        // Twiddle the CPU's thumbs for 4 seconds
        // Then, call the function.
    } // end of showProduct( )

    $(document).ready(function() {

        $(document).on('click', '.delete', function() {
            var product_id = $(this).attr("id");
            var product = 'delete';

            $.ajax({
                url: "productAction.php",
                method: "POST",
                data: {
                    product_id: product_id,
                    product: product
                }
                // success:function(data){
                //     $('#alert_action').fadeIn().html('<div class="alert alert-info">'+data+'</div>');
                //     productdataTable.ajax.reload();
                // }
            });

            // if (confirm("Are you sure you want to change status?")) {

            // } else {
            //     return false;
            // }
        });
    });
</script>
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
                        <button type="button" name="add" id="add_button" class="btn btn-success btn-xs">Add New Product</button>
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
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="productList"></div>

<script>
    showProduct()
</script>




<?php
include('footer.php');

?>