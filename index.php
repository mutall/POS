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
            </section>
        </div>

        <section id="stock1" class="sect">
            <button class="big-button" onclick="showStock(3)">STORE STOCK</button>
            <button class="big-button" onclick="showStock(1)">UPPER CHICJOINT COUNTER</button>
            <button class="big-button" onclick="showStock(2)">LOWER CHICJOINT COUNTER</button>
        </section>

        <section id="counter-chooser" class="sect">
            
        </section>
    </div>
</body>
<script src="./scripts/navigator.js"></script>

</html>