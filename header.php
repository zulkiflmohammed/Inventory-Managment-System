<!-- header.php - header for all pages
    Course: CSC235- prjFinal
    Written by: Zulkifl Mohammed
    Email: mohammez@csp.edu
    Written:    12/15/19
    Revised:   
    -->

<?php
//header.php
?>
<!DOCTYPE html>
<html>

<head>
    <title>Inventory Management System</title>
    <!-- jQuery first, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-2.1.4.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">


    <!-- <link rel="stylesheet" href="css/dataTables.bootstrap.min.css" /> -->


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

                <!-- <ul class="nav navbar-nav">
                    <li><a href="product.php">Product</a></li>
                    <li><a href="department.php">Department</a></li>
                    <li><a href="supplier.php">Suppliers</a></li>
                </ul> -->

                <!-- <ul class="nav navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown"><span class="label label-pill label-danger count"></span> <?php echo $_SESSION["user_name"]; ?>Admin</a>
                        <ul class="dropdown-menu">
                            <li><a href="profile.php">Profile</a></li>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul> -->

            </div>
        </nav>