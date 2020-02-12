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
  <link rel="stylesheet" href="assets/css/main.css">
  <link rel="stylesheet" href="assets/css/stisla.css">
  <link rel="stylesheet" href="assets/css/components.css">

  <script>
    fetch('lib/chicjoint.php', {
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
            <input class="form-control" type="search" placeholder="Search" aria-label="Search" data-width="250">
            <button class="btn" type="submit"><i class="fas fa-search"></i></button>
            <div class="search-backdrop"></div>
          </div>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
              <img alt="image" src="assets/images/avatar-1.png" class="rounded-circle mr-1">
              <div class="d-sm-none d-lg-inline-block">Hi, Emma</div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
              <div class="dropdown-title">Logged in 5 min ago</div>
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
              <a href="#" class="dropdown-item has-icon text-danger">
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
              <a href="#" class="nav-link"><i class=" fas fa-columns"></i> <span>STOCK</span></a>
            </li>
            <li target="book-keeping"><a class="nav-link" href="#"><i class="far fa-square"></i> <span>BOOK KEEPING</span></a></li>
            <li onclick="test()"><a class="nav-link" href="#"><i class="fas fa-pencil-ruler"></i> <span>Credits</span></a></li>
          </ul>
        </aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
        <section class="section active" id="dashboard">
          <div class="section-header">
            <h1>DASHBOARD</h1>
          </div>

          <div class="section-body">
            <h2>😎😎 Something Awesome is coming soon 😎😎</h2>
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
            <div class="card pop-header">
              <div class="card-body">
                <table class="table">
                  <thead>
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
                  <!-- <button class="btn btn-primary">Print</button> -->
                </table>
              </div>
            </div>
            <div class="card">
              <div class="card-header">
                <h4>RESULTS</h4>
              </div>
              <div class="card-body">
                <table class="table ">
                  <thead>
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
        <section class="section" id="book-keeping">
          <div class="section-header">
            <h1>book keeping</h1>
          </div>

          <div class="section-body">
            <div class="card p-3 d-flext flex-row justify-content-around border-dark">
              <div class="card-image bg-primary">
                <img src="assets/images/no_image.jpg" width="200" id="pombe-image" alt="" srcset="">
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
                      <input type="number" class="form-control mr-3" id="stock" placeholder="Opening stock">
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
              <table class="table table-sm table-bordered">
                <thead>
                  <th>PRODUCT NAME</th>
                  <th>QUANTITY</th>
                </thead>
                <tbody id="temp-body">

                </tbody>
              </table>
            </div>
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
  <script src="assets/js/stisla.js"></script>

  <!-- JS Libraies -->

  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/main.js"></script>

  <!-- Page Specific JS File -->
</body>

</html>