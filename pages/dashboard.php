<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>CHICJOINT &mdash; POS</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
  <!-- <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css"> -->
  <!-- Template CSS -->
  <link rel="stylesheet" href="../assets/css/main.css">
  <link rel="stylesheet" href="../assets/css/stisla.css">
  <link rel="stylesheet" href="../assets/css/components.css">
  <link rel="stylesheet" href="../assets/css/iziToast.min.css">

  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css">



  <script>
    fetch('../lib/chicjoint.php', {
        method: "post",
        body: JSON.stringify({
          class: 'Chicjoint',
          method: 'init',
          state: false
        })
      })
      .then(response => response.json())
      .then(result => saveToLs(result))
      .catch(err => console.error(err))
    const saveToLs = data => {
      storage = window.localStorage
      for ([key, value] of Object.entries(data)) {
        storage.setItem(key, JSON.stringify(value))
      }
    }
  </script>
</head>

<body>
  <div id="app">
    <div class="main-wrapper">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i class="fas fa-search"></i></a></li>
          </ul>
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
      <div class="main-sidebar">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="index.php">CHIC JOINT POS</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="index.php">POS</a>
          </div>
          <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            <li class="active" target="dashboard"><a href="#" class="nav-link"><i class="fas fa-fire"></i><span>Dashboard</span></a></li>
            <li class="menu-header">Main Menu</li>
            <li target="stock">
              <a href="#" class="nav-link"><i class=" fas fa-columns"></i> <span>Stock</span></a>
            </li>
            <li target="bookkeeping"><a class="nav-link" href="#"><i class="far fa-square"></i> <span>Book Keeping</span></a></li>
            <li target="setting"><a class="nav-link" href="#"><i class="fas fa-cog"></i><span>Setting</span></a></li>
            <li onclick="test()"><a class="nav-link" href="#"><i class="fas fa-pencil-ruler"></i> <span>Credits</span></a></li>
          </ul>
        </aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section active" id="dashboard">
          <div class="row">
            <div class="col-lg-4 col-md-4 col-sm-12">
              <div class="card card-statistic-2">
                <div class="card-stats">
                  <div class="card-stats-title">Order Statistics -
                    <div class="dropdown d-inline">
                      <a class="font-weight-600 dropdown-toggle" data-toggle="dropdown" href="#" id="orders-month">February</a>
                      <ul class="dropdown-menu dropdown-menu-sm">
                        <li class="dropdown-title">Select Month</li>
                        <li><a href="#" class="dropdown-item">January</a></li>
                        <li><a href="#" class="dropdown-item">February</a></li>
                        <li><a href="#" class="dropdown-item">March</a></li>
                        <li><a href="#" class="dropdown-item">April</a></li>
                        <li><a href="#" class="dropdown-item">May</a></li>
                        <li><a href="#" class="dropdown-item">June</a></li>
                        <li><a href="#" class="dropdown-item">July</a></li>
                        <li><a href="#" class="dropdown-item active">August</a></li>
                        <li><a href="#" class="dropdown-item">September</a></li>
                        <li><a href="#" class="dropdown-item">October</a></li>
                        <li><a href="#" class="dropdown-item">November</a></li>
                        <li><a href="#" class="dropdown-item">December</a></li>
                      </ul>
                    </div>
                  </div>
                  <div class="card-stats-items">
                    <div class="card-stats-item">
                      <div class="card-stats-item-count">24</div>
                      <div class="card-stats-item-label">BEER</div>
                    </div>
                    <div class="card-stats-item">
                      <div class="card-stats-item-count">12</div>
                      <div class="card-stats-item-label">SODA</div>
                    </div>
                    <div class="card-stats-item">
                      <div class="card-stats-item-count">23</div>
                      <div class="card-stats-item-label">SPIRITS</div>
                    </div>
                  </div>
                </div>
                <div class="card-icon shadow-primary bg-primary">
                  <i class="fas fa-archive"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Total Sales today</h4>
                  </div>
                  <div class="card-body">
                    59
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
              <div class="card card-statistic-2">
                <div class="card-chart">
                  <canvas id="balance-chart" height="80"></canvas>
                </div>
                <div class="card-icon shadow-primary bg-primary">
                  <i class="fas fa-money-bill-wave-alt"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Revenue</h4>
                  </div>
                  <div class="card-body">
                    Ksh187,13
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12">
              <div class="card card-statistic-2">
                <div class="card-chart">
                  <canvas id="sales-chart" height="80"></canvas>
                </div>
                <div class="card-icon shadow-primary bg-primary">
                  <i class="fas fa-shopping-bag"></i>
                </div>
                <div class="card-wrap">
                  <div class="card-header">
                    <h4>Sales</h4>
                  </div>
                  <div class="card-body">
                    232
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-7">
              <div class="card">
                <div class="card-header">
                  <h4>Invoices</h4>
                  <div class="card-header-action">
                    <a href="#" class="btn btn-danger">View More <i class="fas fa-chevron-right"></i></a>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive table-invoice">
                    <table class="table table-striped">
                      <tr>
                        <th>Invoice ID</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th>Due Date</th>
                        <th>Action</th>
                      </tr>
                      <tr>
                        <td><a href="#">INV-87239</a></td>
                        <td class="font-weight-600">Samuel Kanyi</td>
                        <td>
                          <div class="badge badge-warning">Unpaid</div>
                        </td>
                        <td>February 14, 2020</td>
                        <td>
                          <a href="#" class="btn btn-primary">Detail</a>
                        </td>
                      </tr>
                      <tr>
                        <td><a href="#">INV-48574</a></td>
                        <td class="font-weight-600">Peter Muraya</td>
                        <td>
                          <div class="badge badge-success">Paid</div>
                        </td>
                        <td>January 21, 2020</td>
                        <td>
                          <a href="#" class="btn btn-primary">Detail</a>
                        </td>
                      </tr>
                      <tr>
                        <td><a href="#">INV-76824</a></td>
                        <td class="font-weight-600">Raphael Lantei</td>
                        <td>
                          <div class="badge badge-warning">Unpaid</div>
                        </td>
                        <td>February 14, 2020</td>
                        <td>
                          <a href="#" class="btn btn-primary">Detail</a>
                        </td>
                      </tr>
                      <tr>
                        <td><a href="#">INV-84990</a></td>
                        <td class="font-weight-600">Dennis Njuguna</td>
                        <td>
                          <div class="badge badge-warning">Unpaid</div>
                        </td>
                        <td>February 14, 2020</td>
                        <td>
                          <a href="#" class="btn btn-primary">Detail</a>
                        </td>
                      </tr>
                      <tr>
                        <td><a href="#">INV-87320</a></td>
                        <td class="font-weight-600">Chris Mithamo</td>
                        <td>
                          <div class="badge badge-success">Paid</div>
                        </td>
                        <td>January 28, 2020</td>
                        <td>
                          <a href="#" class="btn btn-primary">Detail</a>
                        </td>
                      </tr>
                    </table>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-5">
              <div class="card">
                <div class="card-header">
                  <h4>Monthly Sales</h4>
                </div>
                <div class="card-body">
                  <canvas id="myChart" height="158"></canvas>
                </div>
              </div>
            </div>
          </div>
          <div class="row">

          </div>
        </section>
        <section class="section" id="stock">
          <div class="section-header">
            <h1>stock</h1>
          </div>

          <div class="section-body">
            <div class="card">
              <div class="card-header">
                <h4>SELECT STATION AND DATE</h4>
              </div>
              <div class="card-body">
                <form id="station-form" class="form-inline ">
                  <div class="form-group col-md-4">
                    <select id="stationInputState" class="form-control">
                      <option selected>SELECT STATION</option>
                    </select>
                  </div>
                  <label class="sr-only" for="inlineFormInputGroupUsername2">Username</label>
                  <div class="input-group mb-2 mr-sm-2">
                    <input type="date" class="form-control" id="stockDate" placeholder="Username">
                  </div>
                  <div class="card-footer text-right">
                    <button class="btn btn-primary mr-1" type="submit">Submit</button>
                    <button class="btn btn-secondary" type="reset">Reset</button>
                  </div>
                </form>
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                <h4>RESULTS</h4>
              </div>
              <div class="card-body">
                <table class="table table-striped table-bordered" id="stock-table">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col">Product</th>
                      <th scope="col">Opening Stock</th>
                      <th scope="col">Added Stock</th>
                      <th scope="col">Total Stock</th>
                      <th scope="col">Closing Stock</th>
                      <th scope="col">Sales Stock</th>
                      <th scope="col">Selling Price</th>
                      <th scope="col">Amount</th>
                    </tr>
                  </thead>
                  <tbody class="result-body">

                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </section>
        <section class="section" id="bookkeeping">
          <div class="section-header d-flex justify-content-between">
            <h1>book keeping</h1>

            <div class="d-flex justify-content-around">
              <span id="remainder" class="mr-5">96 remaining</span>
              <button class="btn btn-primary mr-2" id="upload">UPLOAD TABLE</button>
              <button class="btn btn-warning" id="clear">CLEAR TABLE</button>
            </div>
          </div>

          <div class="section-body">
            <div class="card p-3 d-flext flex-row justify-content-around border-dark">
              <div class="card-image bg-primary">
                <img src="" id="pombe-image" alt="" srcset="">
              </div>
              <div class="details">
                <h5 class="card-header text-center" id="product-name">TUSKER LAGER</h5>
                <p class="card-text">Closing Stock: <span id="quantity">170</span></p>
                <p class="card-text">Date:<span id="date"> 01/01/2020</span></p>
              </div>
              <div>
                <div>
                  <form id="submit-drink">
                    <div class="form-group">
                      <label for="stock">Opening Stock</label>
                      <input type="number" class="form-control mr-3" id="stockInput" placeholder="Opening stock">
                    </div>

                    <div class="buttons d-flex">
                      <input type="submit" class="btn btn-primary" id="btn-submit" value="UPLOAD">
                      <input type="reset" class="btn btn-warning" id="reset-btn" value="CLEAR">
                    </div>
                  </form>

                </div>
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
        </section>
        <section class="section" id="setting">
          <div class="section-header">
            <h1>Settings</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item">Settings</div>
            </div>
          </div>

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
                  <img src="../assets/images/balozi.jpg" id="Pimage" alt="balozi" srcset="">
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

        <section class="section" id="resulttable">
        <div class="section-header">
            <h1>RESULTS</h1>
            <div class="section-header-breadcrumb">
              <div class="breadcrumb-item active"><a href="#">Dashboard</a></div>
              <div class="breadcrumb-item active">Settings</div>
              <div class="breadcrumb-item">Products</div>
            </div>
          </div>
          <div class="section-body">
          <table class="table table-striped table-bordered" id="result-book-table">
                  <thead class="thead-dark">
                    <tr>
                      <th scope="col">Product</th>
                      <th scope="col">Opening Stock</th>
                      <th scope="col">Added Stock</th>
                      <th scope="col">Total Stock</th>
                      <th scope="col">Closing Stock</th>
                      <th scope="col">Sales Stock</th>
                      <th scope="col">Selling Price</th>
                      <th scope="col">Amount</th>
                    </tr>
                  </thead>
                  <tbody class="result-body">

                  </tbody>
                </table>
          </div>
        </section>

      </div>
      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; 2020 <div class="bullet"></div> Design By <a target="_blank" href="https://mutalldevs.co.ke">MUTALL DEVS</a>
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
  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.3/dist/Chart.min.js"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
  <script src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
  <script src="../assets/js/lib/stisla.js"></script>
  <script src="../assets/js/lib/iziToast.min.js"></script>
  <script src="../assets/js/lib/sweetalert.min.js"></script>

  <!-- JS Libraies -->

  <!-- Template JS File -->
  <script src="../assets/js/lib/scripts.js"></script>
  <script src="../assets/js/main.js"></script>
  <script>
    var ctx = document.getElementById("myChart").getContext('2d');
    var myChart = new Chart(ctx, {
      type: 'line',
      data: {
        labels: ["January", "February", "March", "April", "May", "June", "July", "August"],
        datasets: [{
          label: 'Sales',
          data: [3200, 1800, 4305, 3022, 6310, 5120, 5880, 6154],
          borderWidth: 2,
          backgroundColor: 'rgba(63,82,227,.8)',
          borderWidth: 0,
          borderColor: 'transparent',
          pointBorderWidth: 0,
          pointRadius: 3.5,
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: 'rgba(63,82,227,.8)',
        }]
      },
      options: {
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            gridLines: {
              // display: false,
              drawBorder: false,
              color: '#f2f2f2',
            },
            ticks: {
              beginAtZero: true,
              stepSize: 1500,
              callback: function(value, index, values) {
                return 'Ksh ' + value;
              }
            }
          }],
          xAxes: [{
            gridLines: {
              display: false,
              tickMarkLength: 15,
            }
          }]
        },
      }
    });

    var balance_chart = document.getElementById("balance-chart").getContext('2d');

    var balance_chart_bg_color = balance_chart.createLinearGradient(0, 0, 0, 70);
    balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
    balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

    var myChart = new Chart(balance_chart, {
      type: 'line',
      data: {
        labels: ['16-07-2018', '17-07-2018', '18-07-2018', '19-07-2018', '20-07-2018', '21-07-2018', '22-07-2018', '23-07-2018', '24-07-2018', '25-07-2018', '26-07-2018', '27-07-2018', '28-07-2018', '29-07-2018', '30-07-2018', '31-07-2018'],
        datasets: [{
          label: 'Revenue',
          data: [50, 61, 80, 50, 72, 52, 60, 41, 30, 45, 70, 40, 93, 63, 50, 62],
          backgroundColor: balance_chart_bg_color,
          borderWidth: 3,
          borderColor: 'rgba(63,82,227,1)',
          pointBorderWidth: 0,
          pointBorderColor: 'transparent',
          pointRadius: 3,
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: 'rgba(63,82,227,1)',
        }]
      },
      options: {
        layout: {
          padding: {
            bottom: -1,
            left: -1
          }
        },
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            gridLines: {
              display: false,
              drawBorder: false,
            },
            ticks: {
              beginAtZero: true,
              display: false
            }
          }],
          xAxes: [{
            gridLines: {
              drawBorder: false,
              display: false,
            },
            ticks: {
              display: false
            }
          }]
        },
      }
    });

    var sales_chart = document.getElementById("sales-chart").getContext('2d');

    var sales_chart_bg_color = sales_chart.createLinearGradient(0, 0, 0, 80);
    balance_chart_bg_color.addColorStop(0, 'rgba(63,82,227,.2)');
    balance_chart_bg_color.addColorStop(1, 'rgba(63,82,227,0)');

    var myChart = new Chart(sales_chart, {
      type: 'line',
      data: {
        labels: ['16-07-2018', '17-07-2018', '18-07-2018', '19-07-2018', '20-07-2018', '21-07-2018', '22-07-2018', '23-07-2018', '24-07-2018', '25-07-2018', '26-07-2018', '27-07-2018', '28-07-2018', '29-07-2018', '30-07-2018', '31-07-2018'],
        datasets: [{
          label: 'Sales',
          data: [70, 62, 44, 40, 21, 63, 82, 52, 50, 31, 70, 50, 91, 63, 51, 60],
          borderWidth: 2,
          backgroundColor: balance_chart_bg_color,
          borderWidth: 3,
          borderColor: 'rgba(63,82,227,1)',
          pointBorderWidth: 0,
          pointBorderColor: 'transparent',
          pointRadius: 3,
          pointBackgroundColor: 'transparent',
          pointHoverBackgroundColor: 'rgba(63,82,227,1)',
        }]
      },
      options: {
        layout: {
          padding: {
            bottom: -1,
            left: -1
          }
        },
        legend: {
          display: false
        },
        scales: {
          yAxes: [{
            gridLines: {
              display: false,
              drawBorder: false,
            },
            ticks: {
              beginAtZero: true,
              display: false
            }
          }],
          xAxes: [{
            gridLines: {
              drawBorder: false,
              display: false,
            },
            ticks: {
              display: false
            }
          }]
        },
      }
    });
  </script>

  <!-- Page Specific JS File -->
</body>

</html>