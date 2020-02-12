<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://kit.fontawesome.com/65e6a24881.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Cinzel|Nunito|Sulphur+Point&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/animate.css" />
    <title>POINT OF SALE</title>
</head>

<body>
    <div class="container">
        <h1 class="text-center">POINT OF SALE</h1>
        <h3 class="text-center">DASHBOARD</h3>
        <ul class="breadcrumbs">
            <li target="home" class="active-crumb"><span id="span-home">home</span></li>
        </ul>
        <div class="content">
            <section id="home" target="home" class="sect active">
                <button class="stock big-button" target="stock1">STOCK</button>
                <button class="stock big-button" target="book-keeping">BOOK KEEPING</button>
            </section>
        </div>

        <section id="stock1" class="sect">
            <button class="big-button" target="content-table">STORE STOCK</button>
            <button class="big-button" onclick="Server.connectServer('lib/stock.php', buildTable)">UPPER CHICJOINT COUNTER</button>
            <button class="big-button" target="content-table">LOWER CHICJOINT COUNTER</button>
        </section>

        <section id="book-keeping" class="sect">
            <button class="big-button" onclick="Server.connectServer('lib/closing_stock.php', openingStock)">Opening Stock</button>
            <!-- <button class="big-button" onclick="Navigator.openModal(`staff-selector`)">Opening Stock</button> -->
        </section>


        <section id="stock-entry" class="sect">
            <div class="d-lg-inline-flex w-100 align-items-center justify-content-end mb-1">
                <input class="animated slideInRight" type="text" name="" id="search" placeholder="search">
                <span class="ml-5" id="search-icon-plus"><i class="fas fa-search-plus fa-2x"></i></span>
                <span class="ml-5" id="search-icon-minus"><i class="fas fa-times fa-2x"></i></span>
            </div>
            <div class="card p-3 d-flext flex-row justify-content-around border-dark">
                <div class="card-image bg-primary">
                    <img src="images/no_image.jpg" width="200" id="pombe-image" alt="" srcset="">
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
                                <input type="submit" id="btn-submit" value="UPLOAD">
                                <input type="reset" id="reset-btn" value="CLEAR">
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
        </section>
        <section id="content-table" class="sect">
            <h3 id="table-header"></h3>
            <button class="btn btn-primary" onclick="window.print()">PRINT TABLE</button>
            <table class="table">
                <thead>
                    <tr class="staff-date">
                        <th colspan="4" id="staff">STAFF: Emmah</th>
                        <th colspan="4" id="date" style="text-align: right;">DATE: 01/01/2020</th>
                    </tr>
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Opening Stock</th>
                        <th scope="col">Added Stock</th>
                        <th scope="col">Total Stock</th>
                        <th scope="col">Closing Stock</th>
                        <th scope="col">Sales</th>
                        <th scope="col">Selling Price</th>
                        <th scope="col">Amount</th>
                    </tr>
                </thead>
                <tbody id="table-body">

                </tbody>
            </table>

        </section>

        <!-- The Modal -->
        <div id="staff-selector" class="modal animated ">

            <!-- Modal content -->
            <div class="modal-content">
                <span class="close">&times;</span>
                <select>
                    <option value="staff">anne</option>
                    <option value="staff">emmah</option>
                </select>
                <select name="station">
                    <option value="station">Upper chic counter</option>
                    <option value="station">Lower Chic counter</option>
                    <option value="station">Store</option>
                </select>
                <input type="date" id="date" value="<?php echo date('Y-m-d', strtotime(date('Y/m/d'))); ?>">
                <button onclick="updateStaff()">NEXT</button>

            </div>

        </div>
    </div>
</body>
<script src="./scripts/main.js"></script>

</html>