class BarCode {
    constructor() {
        this.initializeElements();
        this.setListeners();
    }

    initializeElements() {
        //get the input
        this.input = document.querySelector("#barcode");

        // Get the modal
        this.modal = document.getElementById("myModal");

        // Get the button that opens the modal
        this.btn = document.getElementById("myBtn");

        // Get the <span> element that closes the modal
        this.span = document.getElementsByClassName("close")[0];

    }

    setListeners() {
        this.input.focus();
        // When the user clicks on the button, open the modal
        this.btn.onclick = ()=> {
            this.modal.style.display = "block";
            this.animateCSS("#myModal", "zoomIn");
        }

        // When the user clicks on <span> (x), close the modal
        this.span.onclick = () =>{
            this.modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = (event)=> {
            if (event.target == this.modal) {
                this.modal.style.display = "none";
            }
        }
        this.input.onchange = (e) =>this.sendToDb(e.target.value)
    }


    async sendToDb(barcode) {
        var response = await fetch("insert.php", {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                barcode: barcode
            })
        });
    
        if (response.status == 404) {
            console.log("Barcode not found");
            let barcode = this.input.value
            document.querySelector("#unique").value = barcode;
            this.btn.click()
            // launchForm();
        } else if (response.status == 201) {
            console.log("value created");
            const add_product = document.querySelector(".add-product");
            add_product.style.display = "block";
            this.input.value = "";
        }else if(response.status == 202){
            const quantity = document.querySelector(".quantity");
            quantity.style.display = "block";
            this.input.value = "";
        
        }else {
            console.log(response.status);
            console.log("something unexpected happened");
        }
    }
}

new BarCode;