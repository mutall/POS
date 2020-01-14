<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/animate.css" />
    <link href="https://fonts.googleapis.com/css?family=Sulphur+Point&display=swap" rel="stylesheet">
    <title>Point Of Sale</title>

</head>

<body>
    <div class="wrapper-barcode">
        <div class="add-product success-alert animated slideInDown">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            NEW PRODUCT ADDED
        </div>

        <div class="quantity success-alert animated slideInDown">
            <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            PRODUCT QUANTITY UPDATED
        </div>
        <h3>INVENTORY MANAGEMENT</h3>
        <label for="check">
            <input type="checkbox" name="multiple" id="check" value="multiple"  />
        Scan Multiple
        </label>
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
                    <input type="number" name="quantity" id="quantity">
                </div>
                <div class="row">
                    <label for="amount">Enter Amount:</label>
                    <input type="number" name="amount" id="amount" required>
                </div>
                <div class="buttons">
                    <input type="submit" id="upload-btn" value="UPLOAD">
                    <input type="reset" id="reset-btn" value="CLEAR">
                </div>
            </form>
        </div>

    </div>
    
    
    <!-- The Modal -->
    <div id="quantity-modal" class="modal animated">

        <!-- Modal content -->
        <div class="modal-content">
            <span class="close">&times;</span>
            <form id="quantity-form">
                 <div class="row">
                    <label for="scanned">Enter Barcode:</label>
                    <input type="text" name="scanned" id="scanned" required>
                </div>
                
                <div class="row">
                    <label for="quantity">Enter Quantity:</label>
                    <input type="number" name="quantity" id="quantity" required>
                </div>
            
                <div class="buttons">
                    <input type="submit" id="upload-btn" value="UPLOAD">
                    <input type="reset" id="reset-btn" value="CLEAR">
                </div>
            </form>
        </div>
    </div>
    <script src="../scripts/main.js"></script>
</body>

</html>