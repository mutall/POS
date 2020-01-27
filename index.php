<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/main.css">
    <title>POINT OF SALE</title>
</head>

<body>
    <div class="container">
        <h1 class="text-center">POINT OF SALE</h1>
        <h3 class="text-center">DASHBOARD</h3>

        <div class="content mt-5">
            <section id="s1" class="sect active">
                <button class="stock big-button" target="stock1">
                    STOCK
                </button>

                <button class="stock big-button" target="book-keeping">
                    BOOK KEEPING
                </button>
            </section>
        </div>

        <section id="stock1" class="sect">
            <button class="big-button" target="content-table">STORE STOCK</button>
            <button class="big-button" onclick="showStock()">UPPER CHICJOINT COUNTER</button>
            <button class="big-button" target="content-table">LOWER CHICJOINT COUNTER</button>
        </section>

        <section id="book-keeping" class="sect">
            <button class="big-button" onclick="openingStock()">Opening Stock</button>
        </section>


        <section id="stock-entry" class="sect">
            <div class="card p-5 d-flext justify-content-center">
                <h5 class="card-header text-center" id="product-name">TUSKER LAGER</h5>
                <div class="card-body">
                    <p class="card-text">Closing Stock: <span id="quantity">170</span></p>
                    <div class="form-inline">
                        <input type="number" class="form-control mr-3" id="stock" placeholder="Opening stock">
                        <button class="btn btn-primary">submit</button>
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
    </div>
</body>
<script src="./scripts/navigator.js"></script>

</html>

<!-- c -->