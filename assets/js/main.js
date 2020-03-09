//TODO save both the table and session details to the database

//speed up debbuging, declare a preset section
var activeSection = "Dashboard";
//create the main entrypoint for our

const POS = (() => {

    return {
        showClock: () => {
            const time = moment().format('MMMM Do YYYY, h:mm:ss a')
            document.querySelector('.time').innerHTML = time
            setTimeout('POS.showClock()', 1000)
        },
        updateLoggedInTime: () => {
            const time = moment(Storage.getFromLs('loggedIn'), 'hmmss').fromNow()
            document.querySelector('.logged-in').innerHTML = `LOGGED ${time}`
            setTimeout('POS.updateLoggedInTime()', 60000)
        },
        start: () => {

            Storage.saveToLs('loggedIn', moment().format('hmmss'))
            POS.showClock()
            POS.updateLoggedInTime()

            iziToast.success({
                title: 'Logged In',
                message: 'Welcom back Emma',
                position: 'bottomCenter'
            });
            const list_sidebar = document.querySelector('.sidebar-menu')
            const list_items = list_sidebar.querySelectorAll('li')

            list_items.forEach(item => {
                if (item.hasAttribute('target')) {
                    item.addEventListener('click', () => {
                        //set the list item as active
                        list_sidebar.querySelector('.active').classList.remove('active')
                        item.classList.add('active')


                        const section = item.getAttribute('target')
                        //evaluate the clas
                        eval(`new ${Utils.toTitleCase(section)}`)
                    })
                }
            });

            eval(`new ${Utils.toTitleCase(activeSection)}`);
        },
        stop: () => {
            swal({
                title: "LOGOUT?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((logout) => {
                    if (logout) {
                        swal("You hav successfully logged out", {
                            icon: "success",
                        });
                        setTimeout(() => {
                            window.location.href = "../index.php"
                        }, 2000)
                    } else {
                        swal("Logout cancelled");
                    }
                });
        }
    }
})();

//create my own exception object 
class UserException {
    constructor(message) {
        this.message = message
        this.name = 'UserException'

        swal({
            title: "Exception!!",
            text: message,
            icon: "warning",
            dangerMode: true,
        })
    }

}

//create a util class containing utitlity methods
class Utils {
    //function to convert a string to sentence case i.e foo->Foo bar->Bar 
    static toTitleCase(str) {
        return str.replace(
            /\w\S*/g,
            function (txt) {
                return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
            }
        );
    }
}

//create a class for manipulating data in local storage
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
            return this.getFromLs(id)
        }
    }

    static removeFromLs(key, id) {
        const data = this.getFromLs(key)
        const new_data = data.filter(value => value.primary !== id)
        this.saveToLs(key, new_data)
    }

    static delete(key) {
        this.storage.removeItem(key)
    }
}

//create a class or fetching data from server
class Server {
    static url = '../lib/Chicjoint.php'

    static async get(callback) {
        const response = await fetch(this.url)
        if (await response.status === 200) {
            callback(await response.json())
        } else {
            console.error(`Server came bak with status ${await response.status} body: ${await response.body}`);

        }
    }

    static async post(data, callback) {
        const response = await fetch(this.url, {
            method: 'post',
            body: JSON.stringify(data)
        })
        if (await response.status === 200) {
            const x = "jhdfgjghaeg"
            callback(await response.json())
        } else {
            console.error(`Server came bak with status ${await response.status} body: ${await response.body}`);

        }
    }

}

//create a class section in which each individual section will inherit from
class Section {
    constructor() {
        //get the id of the section
        this.id = this.constructor.name
        this.section = document.querySelector(`#${this.id.toLowerCase()}`)
        this.init();
        this.loadElements(this.section);
        this.addListeners();
        this.search(document.querySelector("#search-box"));
    }

    init() {
        document.querySelector('section.active').classList.remove('active')
        document.querySelector(`#${this.id.toLowerCase()}`).classList.add('active')

    }

    loadElements() {
        throw new UserException(`Must implement ${this.loadElements.name} in child classes`)
    }

    addListeners() {
        throw new UserException(`Must implement ${this.addListeners.name} in child classes`)
    }

    search(elem) {
        //dont show the search bar if child clases havent implemented the method
        document.querySelector('#search-box').parentNode.style.display = 'none';

        return false;
    }

    static switch(target) {
        // document.querySelector('section.active').classList.remove('active')
        // document.querySelector(`#${target}`).classList.add('active')
        eval(`new ${target}()`)
    }
}

class Dashboard extends Section {
    constructor() {
        super()
    }

    loadElements(section) {
    }

    addListeners() {
    }


}

class Record extends Section {
    constructor() {
        super()
    }

    loadElements(section) {
        this.stations = Storage.getFromLs('station')
        this.selectElement = section.querySelector('#stationInputState')
        this.stationForm = section.querySelector('#station-form')
        this.stockDate = section.querySelector('#stockDate')
        this.tbody = section.querySelector('.result-body')

    }

    addListeners() {
        this.stations.forEach(station => {
            let optionElem = document.createElement('option')
            optionElem.innerText = station.name.toUpperCase();
            optionElem.setAttribute('id', station.location)
            this.selectElement.appendChild(optionElem)
        })

        this.stationForm.addEventListener('submit', (e) => {
            e.preventDefault()
            const selectedStation = this.selectElement.options[this.selectElement.selectedIndex].id
            const postBody = {
                class: 'Chicjoint',
                method: 'getStock',
                state: true,
                date: this.stockDate.value,
                station: selectedStation
            }
            Server.post(postBody, (data) => {
                const dataArray = []
                // this.tbody.innerHTML = ""
                data.table.forEach(item => {
                    for ([key, value] of Object.entries(item)) {
                        if (value == null) item[key] = 0
                    }

                    item.totalStock = parseInt(item.opening) + parseInt(item.added)
                    item.closingStock = parseInt(item.totalStock) - parseInt(item.sales)
                    item.totalAmount = parseInt(item.sales) * parseInt(item.price)

                    dataArray.push(item)
                })
                console.log(dataArray);

                $(document).ready(function () {
                    $('#stock-table').DataTable({
                        scrollY: 400,
                        retrieve: true,
                        destroy: true,
                        "data": dataArray,
                        "columns": [{
                            "data": "description"
                        },
                            {
                                "data": "opening"
                            },
                            {
                                "data": "added"
                            },
                            {
                                "data": "totalStock"
                            },
                            {
                                "data": "closingStock"
                            },
                            {
                                "data": "sales"
                            },
                            {
                                "data": "price"
                            },
                            {
                                "data": "totalAmount"
                            },
                        ]
                    });

                });

            })
        })

    }


}

class Stock extends Section {
    constructor() {
        super();
        this.deleteLsData();
        //create a copy of products and save to ls to mointor data collected
        Storage.saveToLs('temp-data', Storage.getFromLs('products'));
        //update values
        this.updateVals();
        //save the array to memeory for easy manipulation
        this.data = Storage.getFromLs('temp-data');
        //set the current product for easier massaging
        this.currentProduct = this.data[0];
        //set the first item to be read
        this.addProductItem(this.currentProduct);


    }

    loadElements(section) {
        //load the searchbar
        this.searchBar = document.querySelector('#search-box');

        //remainder for stock left for reading
        this.remainder = section.querySelector("#remainder");
        //initialise the table
        this.table = section.querySelector('#product-table');
        //temp body to append stock
        this.tBody = section.querySelector("#temp-body");
        //image element for pproduct
        this.image = section.querySelector("#pombe-image");
        //form to submit stock
        this.form = section.querySelector('#submit-drink');
        //set product name
        this.productName = section.querySelector("#product-name");
        //barcode for item
        this.productBarcode = section.querySelector("#product-barcode");
        //input for quantity
        this.quantity = section.querySelector('#qty');

        //buttons for sending to or from server
        this.upload = section.querySelector('#upload');
        this.clear = section.querySelector('#clear');

        //this is the session form info. hide this after registering a session
        this.sessionCard = section.querySelector("#session-card");
        //this is the cardfor data entry. unhide this after entering session
        this.entry = section.querySelector(".stock-entry");

        //form to supply session info
        this.sessionForm = section.querySelector("#session-form");
        //drop down selectors for staff and session
        this.staffDropdown = section.querySelector("#staffInputState");
        this.stationDropdown = section.querySelector("#stationInputState");

        //span details
        this.staffSpan = section.querySelector('#staff-detail');
        this.stationSpan = section.querySelector('#station-detail');
        this.dateSpan = section.querySelector('#date-detail');
        this.directionSpan = section.querySelector('#direction-detail');
        this.statusSpan = section.querySelector('#status-detail');


    }

    addListeners() {
        //function for populating dropdowns as soon as they are initialised from the database
        (() => {
            const stations = Storage.getFromLs('station');
            const members = Storage.getFromLs('staff');
            stations.forEach(station => {
                let option = document.createElement('option');
                option.setAttribute('value', station.name);
                option.innerText = station.name;
                this.stationDropdown.appendChild(option);
            });

            members.forEach(member => {
                let option = document.createElement('option');
                option.setAttribute('value', member.name);
                option.innerText = member.name;
                this.staffDropdown.appendChild(option);
            });

            let _this = this;

            //initialise products for datatabe
            $(document).ready(function () {
                const table = $('#product-table').DataTable({
                    scrollY: 400,
                    retrieve: true,
                    destroy: true,
                    "data": Storage.getFromLs("products"),
                    "columns": [{
                        "data": "name"
                    }
                    ]
                });

                $('#product-table tbody').on('click', 'tr', function () {
                    _this.currentProduct = table.row(this).data();
                    _this.addProductItem(_this.currentProduct);
                });
            });

        })();

        //add a listener when the user tries  to submit a form
        this.sessionForm.addEventListener('submit', (e) => {
            e.preventDefault();

            //create a form data object using the form
            const formData = new FormData(this.sessionForm);

            const staff = formData.get("session-staff");
            const station = formData.get("session-station");
            const date = formData.get("session-date");
            const direction = formData.get("session-direction");
            const status = formData.get("session-status");
            console.log(staff);

            const details = {
                staff,
                station,
                date,
                direction,
                status
            };
            //change the span texts
            this.staffSpan.innerText = staff;
            this.stationSpan.innerText = station;
            this.dateSpan.innerText = date;
            this.directionSpan.innerText = direction;
            this.statusSpan.innerText = status;

            Storage.saveToLs('session-details', details);

            this.sessionCard.classList.add("no-display");
            this.entry.style.display = 'block';
        });

        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            const tr = document.createElement('tr');
            const tableTd = document.createElement('td');
            tableTd.innerText = this.currentProduct.name;
            const quantityTd = document.createElement('td');
            quantityTd.innerText = this.quantity.value;

            tr.appendChild(tableTd);
            tr.appendChild(quantityTd);
            this.tBody.appendChild(tr);

            //get table from local storage
            let tableLs = Storage.getFromLs('temp-table');
            tableLs.push({
                name: this.productName.innerHTML,
                quantity: this.quantity.value,
                date: moment().format('YYYY/MM/DD'),
            });
            Storage.saveToLs('temp-table', tableLs);
            console.log(this.currentProduct);
            if (this.data.length > 1) {
                this.data = this.data.filter(item => parseInt(item.product) !== parseInt(this.currentProduct.product));
                this.currentProduct = this.data[0];

                this.addProductItem(this.currentProduct);
                Storage.saveToLs('temp-data', this.data);
                this.updateVals();
            } else {
                //completed data entry show a modal for notifying the user
                alert("finished data entry");
            }


        });

        //this are listeners when the user tries to upload the table
        this.upload.addEventListener('click', () => {
            //set a prompt to ask the user if he/she wants to commit to db
            swal({
                title: "Save Session?",
                text: "Save the current stock taking session?",
                icon: "info",
                buttons: true,
                dangerMode: true
            }).then(ok =>{
                if(ok){
                    const session = Storage.getFromLs('session-details');

                    const postData = {
                        class: "StockSession",
                        method: "init",
                        state: false,
                        data: {
                            session,
                            table: Storage.getFromLs('table'),
                            staff: session.staff,
                            station: session.station
                        }
                    };
                    Server.post(postData, (data) => {
                        console.log(data);

                        swal({
                            title: "NICE!",
                            text: "Successfully uploaded",
                            icon: "success"
                        });

                        this.updateVals();
                        this.clearTable();
                        Section.switch('Record');
                    });
                }else{
                    swal("Ok. Continue 😀");
                }
            })
        });
        this.clear.addEventListener('click', () => {
            swal({
                title: "DELETE TABLE DATA?",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((del) => {
                    if (del) {
                        Storage.delete('table');
                        this.updateVals();
                        this.clearTable();
                        swal("Table deleted", {
                            icon: "success",
                        });
                    } else {
                        swal("Ok. Continue 😀");
                    }
                });
        })

    }

    search(elem) {
        elem.parentNode.style.display = 'block';
        elem.removeEventListener('click', () => {
        });
        elem.addEventListener('change', (e) => {
            e.preventDefault();

            //check if the user has registered a session
            if (this.sessionCard.classList.contains("no-display")) {
                this.data.forEach(item => {
                    if (item.barcode === e.target.value) {
                        this.currentProduct = item;
                        this.addProductItem(this.currentProduct);
                        e.target.value = '';
                        return;
                    }
                })
            } else {
                swal("You must register a session to use search");
            }
            //check if there exists a product with that barcode. in temp directory

        });


    }

    updateVals() {
        const data = Storage.getFromLs('temp-data');
        this.remainder.innerText = `${data.length} Items remaining`;

        const table = Storage.getFromLs('temp-table');
        if (table.length > 0) {
            this.upload.style.display = 'block';
            this.clear.style.display = 'block';
        } else {
            this.upload.style.display = 'none';
            this.clear.style.display = 'none';
        }
    }

    deleteLsData() {
        Storage.delete('data');
        Storage.delete('temp-table');
    }

    addProductItem(item) {
        this.productName.innerText = item.name;
        this.productBarcode.innerText = item.barcode;
        this.image.setAttribute('src', `../assets/images/${item.image}`);
    }

    clearTable() {
        this.tBody.innerHTML = ""
    }
}

class Setting extends Section {
    loadElements(section) {
        this.cards = section.querySelectorAll('.card')


    }

    addListeners() {
        this.cards.forEach(card => {
            if (card.hasAttribute('target')) {
                card.classList.add('clickable-card')
                card.addEventListener('click', () => {
                    const section = card.getAttribute('target')
                    //evaluate the clas
                    eval(`new ${Utils.toTitleCase(section)}`)
                })
            }
        })
    }

    // search() {
    // }
}

class Product extends Section {
    constructor() {
        super()


    }

    loadElements(section) {
        const products = Storage.getFromLs('products')
        const dataArray = Array.from(products, item => [item.name])

        const productName = section.querySelector('#Pname')
        const productBarcode = section.querySelector('#Pbarcode')
        const ProductPrice = section.querySelector('#Pprice')
        const productPicture = section.querySelector('#Pimage')
        $(document).ready(function () {
            const table = $('#product-table').DataTable({
                scrollY: 400,
                paging: false,
                "data": dataArray

            });

            $('#product-table tbody').on('click', 'tr', function () {
                var data = table.row(this).data();
                // alert( 'You clicked on '+data[0]+'\'s row' );

                $.each(products, function (index, value) {
                    if (value.name === data[0]) {
                        productName.value = value.name
                        productBarcode.value = value.barcode
                        ProductPrice.value = value.amount
                        productPicture.setAttribute('src', `../assets/images/${value.image}`)
                    }
                })
            });

        });
        //pproduct rows

        // this.ProductPrice = section.querySelector('#')


    }

    addListeners() {


    }


}

class resulttable extends Section {

    constructor() {
        super()
        const data = Storage.getFromLs('newdata')
        const dataArray = []
        // this.tbody.innerHTML = ""
        data.forEach(item => {
            for ([key, value] of Object.entries(item)) {
                if (value == null) item[key] = 0
            }

            item.totalStock = parseInt(item.opening) + parseInt(item.added)
            item.closingStock = parseInt(item.totalStock) - parseInt(item.sales)
            item.totalAmount = parseInt(item.sales) * parseInt(item.price)

            dataArray.push(item)
        })
        console.log(dataArray);

        $(document).ready(function () {
            $('#result-book-table').DataTable({
                scrollY: 400,
                retrieve: true,
                destroy: true,
                "data": dataArray,
                "columns": [{
                    "data": "description"
                },
                    {
                        "data": "opening"
                    },
                    {
                        "data": "added"
                    },
                    {
                        "data": "totalStock"
                    },
                    {
                        "data": "closingStock"
                    },
                    {
                        "data": "sales"
                    },
                    {
                        "data": "price"
                    },
                    {
                        "data": "totalAmount"
                    },
                ]
            });

        });
    }

    loadElements(section) {
    }

    addListeners() {
    }


}

//start the application 
POS.start();