<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <title>CHICJOINT &mdash; POS</title>

    <!-- General CSS Files -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <!-- Template CSS -->
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="../assets/css/stisla.css">
    <link rel="stylesheet" href="../assets/css/iziToast.min.css">

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">


    <script>
        fetch('../lib/Chicjoint.php', {
                method: "post",
                body: JSON.stringify({
                    class: 'Chicjoint',
                    method: 'init',
                    state: false
                })
            })
            .then(response => response.json())
            .then(result => saveToLs(result))
            .catch(err => console.error(err));
        const saveToLs = data => {
            // console.log(data);
            storage = window.localStorage
            for ([key, value] of Object.entries(data)) {
                storage.setItem(key, JSON.stringify(value))
            }
        }
    </script>
</head>

<body class="layout-3">
    <div id="app">
        <div class="main-wrapper container">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar">
                <div class="navbar-nav">
                    <a href="#" class="nav-link sidebar-gone-show" data-toggle="sidebar"><i class="fas fa-bars"></i></a>
                </div>
                <div class="nav-collapse">
                    <a href="#" class="navbar-brand sidebar-gone-hide">POS DASHBOARD</a>
                    <a class="sidebar-gone-show nav-collapse-toggle nav-link" href="#">
                        <i class="fas fa-ellipsis-v"></i>
                    </a>
                </div>
                <form class="form-inline mr-auto">
                    <div class="search-element">
                        <input id="search-box" class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="250">
                        <button class="btn" type="submit"><i class="fas fa-search"></i></button>
                        <div class="search-backdrop"></div>
                    </div>
                </form>
                <div class="time"></div>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image" src="../assets/images/avatar-1.png" class="rounded-circle mr-1">
                            <div class="d-sm-none d-lg-inline-block">Hi, Emma</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-title logged-in">Logged in 5 min ago</div>
                            <a href="#" class="dropdown-item has-icon">
                                <i class="far fa-user"></i> Profile
                            </a>
                            <a href="#" class="dropdown-item has-icon">
                                <i class="fas fa-bolt"></i> Activities
                            </a>
                            <a href="#" class="dropdown-item has-icon">
                                <i class="fas fa-cog"></i> Settings
                            </a>
                            <div class="dropdown-divider"></div>
                            <a onclick="POS.stop()" href="#" class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>
            <nav class="navbar navbar-secondary navbar-expand-lg">
                <div class="container">
                    <ul class="navbar-nav main-nav">
                        <li class="nav-item active" target="dashboard"><a href="#" class="nav-link"><i class="fas fa-fire"></i><span>HOME</span></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" data-toggle="dropdown" class="nav-link has-dropdown"><i class="far fa-clone"></i><span>SECTIONS</span></a>
                            <ul class="dropdown-menu">
                                <li class="nav-item" target="record"><a href="#" class="nav-link"><i class=" fas fa-columns"></i>
                                        <span>Records</span></a></li>
                                <li class="nav-item" target="stock"><a class="nav-link" href="#"><i class="far fa-square"></i>
                                        <span>Stock Take</span></a>
                                </li>
                                <li class="nav-item" target="report"><a class="nav-link" href="#"><i class="far fa-square"></i>
                                        <span>Reports</span></a>
                                </li>
                                <li class="nav-item" target="setting"><a class="nav-link" href="#"><i class="fas fa-cog"></i><span>Setting</span></a>
                                </li>
                            </ul>
                        </li>


                    </ul>
                    <button class="hide-nav btn btn-outline-warning">HIDE</button>
                </div>
            </nav>



            <!-- Main Content -->
            <div class="main-content">
                <!--Main dashboard section-->
                <section class="section active" id="dashboard">
                    <div class="section-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1" target="stock">
                                    <div class="card-icon bg-primary">
                                        <i class="far fa-square"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header"></div>

                                        <div class="card-body">
                                            <h4 class="text-dark">STOCK</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1" target="record">
                                    <div class="card-icon bg-primary">
                                        <i class="far fa-newspaper"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header"></div>
                                        <div class="card-body">
                                            RECORDS
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1 " target="report">
                                    <div class="card-icon bg-primary">
                                        <i class="far fa-file"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header"></div>

                                        <div class="card-body">
                                            REPORTS
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1" target="setting">
                                    <div class="card-icon bg-primary">
                                        <i class="fas fa-cog"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header"></div>

                                        <div class="card-body">
                                            SETTING
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--Section where the user can access historical records-->
                <section class="section" id="record">
                    <h2 class="section-title">Records of all sessions taken.</h2>
                    <div class="section-body">
                        <div id="session-cards">
                        </div>
                    </div>
                </section>

                <!--Main report section-->
                <section class="section" id="report">
                    <div class="modal" id="modal-selector" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form id="report-form">
                                        <div class="form-group">
                                            <label>Station</label>
                                            <div class="input-group">
                                                <select class="form-control" name="station" id="station">

                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label>Date</label>
                                            <div class="input-group">
                                                <input type="date" required class="form-control" placeholder="date" name="date">
                                            </div>
                                        </div>
                                        <div class="form-group mb-0">
                                            <div class="row d-flex justify-content-center">
                                                <input type="submit" class="btn btn-success mr-3" value="submit">
                                                <input type="reset" class="btn btn-warning" value="clear">
                                            </div>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="section-body">
                        <div class="row">
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1" method="salesReport">
                                    <div class="card-icon bg-primary">
                                        <i class="far fa-file"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header"></div>
                                        <div class="card-body">
                                            <h4 class="text-dark">SALE REPORT</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1" method="stockReport">
                                    <div class="card-icon bg-primary">
                                        <i class="far fa-file"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header"></div>
                                        <div class="card-body">
                                            <h4 class="text-dark">STOCK REPORT</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                                <div class="card card-statistic-1" method="fullReport">
                                    <div class="card-icon bg-primary">
                                        <i class="far fa-file"></i>
                                    </div>
                                    <div class="card-wrap">
                                        <div class="card-header"></div>
                                        <div class="card-body">
                                            <h4 class="text-dark">
                                                FULL DAY REPORT
                                            </h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--Section where the user can input new stock-->
                <section class="section" id="stock">
                    <div class="row d-flex justify-content-between">
                        <h2 class="section-title">STOCK TAKING</h2>
                        <div class="d-flex justify-content-around">
                            <span id="remainder" class="mr-5">96 remaining</span>
                            <button class="btn btn-primary mr-2" id="upload">UPLOAD TABLE</button>
                            <button class="btn btn-warning" id="clear">CLEAR TABLE</button>
                        </div>

                    </div>

                    <div class="section-body">
                        <div id="session-card" class="card d-flex flex-column justify-content-start p-5 col-md-10">
                            <h6 class="text-center card-title">REGISTER SESSION</h6>
                            <div class="card-body">
                                <form action="" id="session-form" class="form">
                                    <div class="form-group">
                                        <label for="staffInputState">SELECT STAFF</label>
                                        <select id="staffInputState" name="session-staff" class="form-control" required>

                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="stationInputState">SELECT STATION</label>
                                        <select id="stationInputState" name="session-station" class="form-control" required>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="date">Session Date</label>
                                        <input type="date" class="form-control" name="session-date" id="date" placeholder="session date" required>
                                    </div>
                                    <div class="form-group">
                                        <span data-toggle="tooltip" data-placement="top" title="Direction stock is coming from" class="mr-3">Session Direction</span>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="session-direction" id="inRadio" value="in">
                                            <label class="form-check-label" for="inRadio">
                                                in
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="session-direction" id="outRadio" value="out">
                                            <label class="form-check-label" for="outRadio">
                                                out
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="session-direction" id="stockRadio" value="stock" checked>
                                            <label class="form-check-label" for="exampleRadios3">
                                                stock
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <span data-toggle="tooltip" data-placement="top" title="status of the session" class="mr-3">Session Status</span>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="session-status" id="inRadio" value="regular" checked>
                                            <label class="form-check-label" for="inRadio">
                                                regular
                                            </label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="session-status" id="outRadio" value="actual">
                                            <label class="form-check-label" for="outRadio">
                                                actual
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group d-flex flex-row justify-content-around">
                                        <input type="submit" class="btn btn-lg btn-primary" value="submit">
                                        <input type="reset" class="btn btn-lg btn-warning" value="reset">
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="stock-entry">
                            <div>
                                <h6 class="text-center">Session Details</h6>
                                <div class="d-flex flex-row p-3 justify-content-around">
                                    <p>station: <span id="station-detail">Counter</span></p>
                                    <p>staff: <span id="staff-detail">Emmah</span></p>
                                    <p>date: <span id="date-detail">12/12/2020</span></p>
                                    <p>direction: <span id="direction-detail">stock</span></p>
                                    <p>status: <span id="status-detail">regular</span></p>
                                    <!--                                <button class="btn btn-sm btn-primary" id="change-detail">change</button>-->
                                </div>
                            </div>
                            <div class="d-flex flex-row justify-content-around">
                                <table class="table-bordered mr-5 " id="product-table">
                                    <thead class="thead-dark">
                                        <tr>
                                            <th>name</th>
                                        </tr>
                                    </thead>
                                    <tbody id="product-tbody"></tbody>
                                </table>
                                <div class="card d-flex flex-column justify-content-around p-5">
                                    <h3 id="product-name" class="text-center">product name</h3>
                                    <h6 id="product-barcode" class="text-center">product barcode</h6>
                                    <div class="product-image">
                                        <img src="#" id="pombe-image" alt="product image" srcset="">
                                    </div>
                                    <form action="" id="submit-drink">
                                        <div class="form-group">
                                            <label for="qty">Quantity</label>
                                            <input type="number" class="form-control form-control-sm" id="qty">
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-outline-primary">
                                            <input type="reset" class="btn btn-outline-danger">

                                        </div>
                                    </form>
                                </div>
                            </div>
                            <div class="mt-5">
                                <table class="table table-bordered" id="book-table">
                                    <thead class="thead-dark">
                                        <th>PRODUCT NAME</th>
                                        <th>QUANTITY</th>
                                    </thead>
                                    <tbody id="temp-body">

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </section>
                <!--this is the main settings page-->
                <section class="section" id="setting">
                    <div class="section-body">
                        <h2 class="section-title">Overview</h2>
                        <p class="section-lead">
                            Organize and adjust all settings about the system.
                        </p>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="card card-large-icons" target="product">
                                    <div class="card-icon bg-primary text-white">
                                        <i class="fas fa-beer"></i>
                                    </div>
                                    <div class="card-body">
                                        <h4>PRODUCTS</h4>
                                        <p>General product settings such as, product names, barcode changes.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card card-large-icons" target="email">
                                    <div class="card-icon bg-primary text-white">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="card-body">
                                        <h4>Email</h4>
                                        <p>Email SMTP settings, notifications and others related to email.</p>
                                        <p class="bg-danger">Not Implemented</p>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="col-lg-6">
                          <div class="card card-large-icons" target="system">
                            <div class="card-icon bg-primary text-white">
                              <i class="fas fa-power-off"></i>
                            </div>
                            <div class="card-body">
                              <h4>System</h4>
                              <p>PHP version settings, time zones and other environments.</p>
                              <a href="#" class="card-cta">Change Setting <i class="fas fa-chevron-right"></i></a>
                            </div>
                          </div>
                        </div> -->
                            <div class="col-lg-6">
                                <div class="card card-large-icons" target="security">
                                    <div class="card-icon bg-primary text-white">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <div class="card-body">
                                        <h4>Security</h4>
                                        <p>Security settings such as firewalls, server accounts and others.</p>
                                        <p class="bg-danger">Not Implemented</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="card card-large-icons" target="automation">
                                    <div class="card-icon bg-primary text-white">
                                        <i class="fas fa-stopwatch"></i>
                                    </div>
                                    <div class="card-body">
                                        <h4>Automation</h4>
                                        <p>Settings about automation such as cron job, backup automation and so on.</p>
                                        <p class="bg-danger">Not Implemented</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--section fr changing product dertails-->
                <section class="section" id="product">
                    <div class="section-header">
                        <h1>Product Settings</h1>
                        <div class="section-header-breadcrumb">
                            <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
                            <div class="breadcrumb-item active">Settings</div>
                            <div class="breadcrumb-item">Products</div>
                        </div>
                    </div>

                    <div class="section-body">
                        <h2 class="section-title">Overview</h2>
                        <p class="section-lead">
                            Adjust all settings about the Products.
                        </p>
                        <div class="d-flex flex-row justify-content-around">
                            <table class="col-sm-6 table-bordered mr-5 " id="product-table">
                                <thead>
                                    <th>name</th>
                                </thead class="thead-dark">
                                <tbody id="product-tbody"></tbody>
                            </table>
                            <div class="card card-large-icons d-flex justify-content-around">
                                <div class="product-image">
                                    <img src="../assets/images/balozi.jpg" id="pombe-image" alt="balozi" srcset="">
                                </div>
                                <div class="card-body product-details d-flex flex-column">
                                    <form action="" class="form">
                                        <div class="form-group">
                                            <label for="Pname">Name</label>
                                            <input type="text" class="form-control form-control-sm" id="Pname">
                                        </div>
                                        <div class="form-group">
                                            <label for="Pbarcode">Barcode</label>
                                            <input type="text" class="form-control form-control-sm" id="Pbarcode">
                                        </div>
                                        <div class="form-group">
                                            <label for="Pprice">Price</label>
                                            <input type="text" class="form-control form-control-sm" id="Pprice">
                                        </div>
                                        <div class="form-group">
                                            <label for="exampleFormControlFile1">Change Image</label>
                                            <input type="file" class="form-control-file" id="exampleFormControlFile1">
                                        </div>
                                        <div class="form-group">
                                            <input type="submit" class="btn btn-primary" value="update">
                                            <input type="clear" class="btn btn-warning" value="clear">
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
                <!--result table after uploading new stock-->
                <section class="section" id="table">
                    <h2 class="section-title">Records of all sessions taken.</h2>
                    <div class="section-body">

                        <div>
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="text-center">Session Details</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex flex-row p-3 justify-content-around">
                                        <p>staff: <span id="staff-detail">Emmah</span></p>
                                        <p>date: <span id="date-detail">12/12/2020</span></p>
                                        <p>station: <span id="station-detail">Counter</span></p>
                                        <p>direction: <span id="direction-detail">stock</span></p>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card">
                            <div class="card-body">
                                <table class="table table-bordered" id="book-table">
                                    <thead class="thead-dark">
                                        <th>PRODUCT NAME</th>
                                        <th>QUANTITY</th>
                                    </thead>
                                    <tbody id="temp-body">

                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </section>

                <section class="section" id="errorpage">
                    <div class="container mt-5">
                        <div class="page-error">
                            <div class="page-inner">
                                <h1 id="status-code">500</h1>
                                <div id="status-message" class="page-description">
                                    Whoopps, something went wrong.
                                </div>
                                <div class="page-search">
                                    <div class="mt-3">
                                        <li target="dashboard"><a href="#">Back to Home</a></li>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="simple-footer mt-5">
                            Copyright &copy; 2020
                        </div>
                    </div>
                </section>

            </div>
            <footer class="main-footer">
                <div class="footer-left">
                    Copyright &copy; 2020
                    <div class="bullet"></div>
                    Design By <a target="_blank" href="https://mutalldevs.co.ke">MUTALL DEVS</a>
                </div>
                <div class="footer-right">
                    1.0.0
                </div>
            </footer>
        </div>
    </div>

    <!-- General JS Scripts -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.nicescroll/3.7.6/jquery.nicescroll.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
    <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
    <script src="../assets/js/lib/iziToast.min.js"></script>
    <script src="../assets/js/lib/sweetalert.min.js"></script>

    <!-- JS Libraies -->

    <script src="../assets/js/main.js"></script>
</body>

</html>