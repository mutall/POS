<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="animate.css" />
    <link href="https://fonts.googleapis.com/css?family=Sulphur+Point&display=swap" rel="stylesheet">
    <title>Point Of Sale</title>

</head>

<body>
    <div class="container">
        <div class="add-product success-alert animated slideInDown">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            NEW PRODUCT ADDED
        </div>

        <div class="quantity success-alert animated slideInDown">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            PRODUCT QUANTITY UPDATED
        </div>
        <h3>INVENTORY MANAGEMENT</h3>
        <input class="animated pulse" type="text" name="" id="barcode" placeholder="BARCODE">
        <!-- Trigger/Open The Modal -->
        <button id="myBtn">ENTER NEW PRODUCT</button>

    </div>


    <!-- The Modal -->
    <div id="myModal" class="modal animated">

        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="form">
                <div class="row">
                    <label for="name">Enter name:</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="row">
                    <label for="unique">Enter Barcode:</label>
                    <input type="text" name="unique" id="unique" required>
                </div>
                <div class="row">
                    <label for="quantity">Enter Quantity:</label>
                    <input type="text" name="quantity" id="quantity">
                </div>
                <div class="row">
                    <label for="amount">Enter Amount:</label>
                    <input type="text" name="amount" id="amount" required>
                </div>
                <div class="buttons">
                    <input type="submit" id="upload-btn" value="UPLOAD">
                    <input type="reset" id="reset-btn" value="CLEAR">
                </div>
            </form>
        </div>

    </div>
    <script src="main.js"></script>
</body>

</html>