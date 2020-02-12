//reate a class for navigaing between sections . 
//this class will handle all navigation elements  associated  by the webpage
class Navigator {
    constructor() {
        this.loadUiElements()
        this.setListeners()
    }

    loadUiElements() {
        this.buttons = document.querySelectorAll("button");
        this.homeSpan = document.querySelector("#span-home")
        this.list = document.querySelector('.breadcrumbs')
        this.modal = document.querySelectorAll('.modal')
        this.modalSpan = document.querySelectorAll('.close')
        this.inputSearch = document.querySelector('#search')
        this.searchPlus = document.querySelector('#search-icon-plus')
        this.searchMinus = document.querySelector('#search-icon-minus')
    }

    setListeners() {
        this.buttons.forEach(button => {
            if (button.hasAttribute('target')) {
                button.addEventListener('click', () => {
                    let target = button.getAttribute("target")
                    Navigator.showSection(target)
                    this.updateBreadCrumbs(target, button.innerHTML)
                })

            }

        })

        this.homeSpan.onclick = () => {
            document.querySelector('.active-crumb').classList.remove('active-crumb')
            this.homeSpan.parentNode.classList.add('active-crumb')
            const target = this.homeSpan.parentNode.getAttribute('target')
            Navigator.showSection(target)
            this.removeBreadCrumbs()
        }

        this.modalSpan.forEach(span => {
            let modal = span.parentNode.parentNode
            span.onclick = () => {
                //remove zoom in animation
                modal.classList.remove("zoomIn");
                //add zoomout animation
                modal.className += " zoomOut";
                //hide the modal
                modal.style.display = "none";
            }
        })

        this.inputSearch.addEventListener('keyup', (e)=>{
            const list = Storage.getFromLs('data')
            // const new_list = list.filter(item => item.name.indexOf(e.target.value.toLowerCase())!= -1)
            const new_list = Array.from(list).forEach(item => item.name.toLowerCase().indexOf(e.target.value.toLowerCase()) != -1)
            console.log(new_list);
            
        })
        this.searchPlus.addEventListener('click', ()=>{
            
            this.inputSearch.style.display = 'block'
            this.searchPlus.style.display = 'none'
            this.searchMinus.style.display = 'block'
        })
        this.searchMinus.addEventListener('click', ()=>{
            this.inputSearch.style.display = 'none'

            this.searchPlus.style.display = 'block'
            this.searchMinus.style.display = 'none'
        
        })

    }


    static showSection(section) {
        document.querySelector(".active").classList.remove("active");
        document.querySelector(`#${section}`).classList.add("active")
    }

    static openModal(modalId) {
        const modal = document.querySelector(`#${modalId}`)

        //remove zoom out animation 
        modal.classList.remove("zoomOut");

        //set an animation to the modal
        modal.className += " zoomIn";

        modal.style.display = 'block'


        window.addEventListener('click', (event) => {
            if (event.target == modal) {
                //remove zoom in animation
                event.target.classList.remove("zoomIn");
                //add zoomout animation
                event.target.className += " zoomOut";

                event.target.style.display = "none";
            }
        })
    }

    updateBreadCrumbs = (target, text) => {
        const li = document.createElement('li')
        li.setAttribute('target', target)
        const span = document.createElement('span')

        span.onclick = () => {
            Navigator.showSection(target)
            document.querySelector('.active-crumb').classList.remove('active-crumb')
            span.parentNode.classList.add('active-crumb')
            this.removeBreadCrumbs()
        }

        span.innerText = text.toLowerCase()
        li.appendChild(span)
        document.querySelector('.active-crumb').classList.remove('active-crumb')
        span.parentNode.classList.add('active-crumb')

        this.list.appendChild(li)
    }

    removeBreadCrumbs() {
        const act = document.querySelector('.active-crumb')
        while (act.nextElementSibling !== null) {
            this.list.removeChild(act.nextElementSibling)
        }
    }
}

new Navigator()

class DataEntry {
    constructor() {
        this.data = Storage.getFromLs('data');
        this.loadElements()
        this.addListeners()
        this.addProductItem()
        Navigator.showSection('stock-entry')

    }
    loadElements() {
        this.productName = document.querySelector("#product-name")
        this.stockQuantity = document.querySelector("#quantity")
        // this.remainder = document.querySelector("")
        this.submit = document.querySelector("#btn-submit")
        // this.back = document.querySelector("")
        this.opening = document.querySelector("#stock")
        this.table = document.querySelector("#temp-body")
        this.image = document.querySelector("#pombe-image")
        this.form = document.querySelector('#submit-drink')
    }
    //add listeners to the page elements
    addListeners() {
        this.submit.addEventListener('click', () => {

            const tr = document.createElement('tr');
            const tableTd = document.createElement('td')
            tableTd.innerText = this.productName.innerHTML
            const openingTd = document.createElement('td')
            openingTd.innerText = this.opening.value
            tr.appendChild(tableTd)
            tr.appendChild(openingTd)
            this.table.appendChild(tr)

            //get table from local storage
            let tableLs = Storage.getFromLs('table')
            tableLs.push({
                name: this.productName.innerHTML,
                quantity: this.opening.value,
                staff: "",
                station: "",
                date: ""
            })
            Storage.saveToLs('table', tableLs)

            this.data.shift()
            this.addProductItem()
            Storage.saveToLs('data', this.data)

        });

        this.form.addEventListener('submit', (e) => e.preventDefault())
        // this.back.addEventListener('click', ()=>{

        // })
    }

    addProductItem() {
        const product = this.data[0];
        console.log(product);
        this.productName.innerHTML = product.name
        this.stockQuantity.innerHTML = product.quantity
        this.opening.value = ""
        this.image.setAttribute('src', `images/${product.image}`)
        this.primary = product.primary;


    }
}

//create a class for controlling localstorage
class Storage {
    static storage = window.localStorage
    static saveToLs(key, value) {
        this.storage.setItem(key, JSON.stringify(value))
    }
    static getFromLs(id) {
        const value = this.storage.getItem(id)
        if (value !== null) {
            return JSON.parse(this.storage.getItem(id))
        } else {
            this.saveToLs(id, [])
        }
    }

    static removeFromLs(key, id) {
        const data = this.getFromLs(key)
        const new_data = data.filter(value => value.primary !== id)
        this.saveToLs(key, new_data)
    }
}

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
        this.stock = document.querySelector("#stock");

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
            if (event.target == this.modal || event.target == this.quantity_modal) {
                //remove zoom in animation
                event.target.classList.remove("zoomIn");
                //add zoomout animation
                event.target.className += " zoomOut";

                event.target.style.display = "none";
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
            const f = new FormData(this.form);
            console.log(f)
            this.sendToDb(f);
        }

        //add an onsubmit to the form 
        this.quantity_form.onsubmit = (e) => {
            e.preventDefault();
            this.sendToDb(JSON.stringify({
                barcode: this.scanned.value,
                stock: this.stock.value
            }));
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

class Server {
    static async connectServer(url, callback, is_get = true, data = null) {
        let response;
        if (is_get) {
            response = await fetch(url);

        } else {
            response = await fetch(url, {
                method: 'POST',
                body: data
            })
        }
        if (await response.status === 200) {
            callback(await response.json())
        }
    }
}


const buildTable = (data) => {
    //build the table  to print
    const tbody = document.querySelector("#table-body")
    data.forEach(item => {
        let tr = document.createElement("tr");

        for (const [key, value] of Object.entries(item)) {
            let myTd = document.createElement('td');
            myTd.innerHTML = value
            tr.appendChild(myTd)
        }

        tbody.appendChild(tr)

    })
    Navigator.showSection('content-table')
}


const openingStock = (data) => {
    // sort the datta using the pprimary keys
    // data.sort((a, b) => parseInt(a.product) - parseInt(b.product))
    //get access to LS
    Storage.saveToLs('data', data)
    new DataEntry()
}