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

        //get the form which is used to submit
        this.form = document.querySelector("#form");
        
        //get the form which is used to submit
        this.quantity_form = document.querySelector("#quantity-form");
        
        //get th quantity modal
        this.quantity_modal = document.querySelector("#quantity-modal");
        
        //get the scanned input
        this.scanned = document.querySelector("#scanned");
        
        //get the check box
        this.checkbox = document.querySelector("#check");
        console.log(this.checkbox.checked);
    }

    setListeners() {
        this.input.focus();
        // When the user clicks on the button, open the modal
        this.btn.onclick = () => {
            //show the modal form
            this.modal.style.display = "block";
            //remove zoom out animation 
            this.modal.classList.remove("zoomOut");

            //set an animation to the modal
            this.modal.className += " zoomIn";
        }

        // When the user clicks on <span> (x), close the modal
        this.span.onclick = () => {
            //remove zoom in animation
            this.modal.classList.remove("zoomIn");
            //add zoomout animation
            this.modal.className += " zoomOut";
            //hide the modal
            this.modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = (event) => {
            if (event.target == this.modal) {
                //remove zoom in animation
                this.modal.classList.remove("zoomIn");
                //add zoomout animation
                this.modal.className += " zoomOut";

                this.modal.style.display = "none";
            }
        }

        //set an on change whenever the input changes
        this.input.onchange = (e) => {
            if (this.checkbox.checked) {
                this.scanned.value = this.input.value;
                this.quantity_modal.style.display = "block";
            } else {

                this.sendToDb(JSON.stringify({
                    barcode: e.target.value

                })

                        )
            }
        };

        //add an onsubmit to the form 
        this.form.onsubmit = (e) => {
            e.preventDefault();
            this.sendToDb(new FormData(this.form));
        }
        
         //add an onsubmit to the form 
        this.quantity_form.onsubmit = (e) => {
            e.preventDefault();
            const formData = new FormData(this.quantity_form);
            formData.append("type", "update");
            this.sendToDb(new FormData(this.form));
        }
    }

    async sendToDb(data) {
        var response = await fetch("insert.php", {
            method: 'POST',
            body: data
        });

        switch (response.status) {
            //this is response code gotten after successfully inserted a new product
            case 201:
                //show an alert telling the user that a product has been added
                document.querySelector(".add-product").style.display = "block";

                //dismiss the alert after 2 seconds
                setTimeout(() => {
                    document.querySelector(".add-product").style.display = "none";
                }, 2000)

                //remove the entrance animation
                this.modal.classList.remove("zoomIn")
                //add a close animation
                this.modal.className += " zoomOut";
                //dismiss modal
                this.modal.style.display = "none";
                //clear the input 
                this.input.value = "";

                break;

                //this is response code gotten after succesfully incrementing quantity of a product
            case 202:
                //show an alert telling the user that a product quantity has been incremented
                document.querySelector(".quantity").style.display = "block";

                //dismiss the alert after 2 seconds
                setTimeout(() => {
                    document.querySelector(".quantity").style.display = "none";
                }, 2000)
                //clear the input
                this.input.value = "";
                break;

                //this is response code gotten if a barcode doesnt exist in the db
            case 404:
                //set the barcode input in form with the scanned barcode
                document.querySelector("#unique").value = this.input.value;
                //show the form
                this.btn.click()
                break;

                //this is response code gotten after a server error has occured
            case 500:
                console.log(response.status);
                console.log(response.body);
                break;
        }

    }

    showAlert(classname) {

    }
}

new BarCode;