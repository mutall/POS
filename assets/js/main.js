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
            const list_navbar = document.querySelector('.main-nav')
            const list_items = list_navbar.querySelectorAll('li')
            // console.log(list_items);
            // console.log(list_navbar);


            list_items.forEach(item => {
                if (item.hasAttribute('target')) {
                    item.addEventListener('click', () => {
                        //set the list item as active
                        list_navbar.querySelector('.active').classList.remove('active')
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
        if (await response.status === 200 || await response.status === 201 || await response.status === 202) {
            callback(await response.json())
        } else {
            const code = await response.status;
            const body = await response.statusText;

            //handle bad response here, open page with associated error code
            new ErrorPage(code, body);
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
        const navBtn = document.querySelector('.hide-nav');
        navBtn.addEventListener('click', () => {
            navBtn.parentNode.parentNode.style.display = "none";
        });

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

    static
    switch (target, data) {
        data = (typeof data !== undefined) ? JSON.stringify(data) : undefined
        // document.querySelector('section.active').classList.remove('active')
        // document.querySelector(`#${target}`).classList.add('active')


        eval(`new ${target}(${data})`)
    }


}

class Dashboard extends Section {
    constructor() {
        super()
    }

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


}

class Record extends Section {
    constructor() {
        super()
    }

    loadElements(section) {
        this.sessionCards = section.querySelector("#session-cards");
    }

    addListeners() {
        //load all session details as soon as the section loads
        (() => {
            const staffData = Storage.getFromLs('staff');
            const stationData = Storage.getFromLs('station');

            const postData = {
                class: 'StockSession',
                method: 'getSessionList',
                state: true
            }

            Server.post(postData, (data) => {
                data.forEach(item => {
                    const staff = staffData.filter(member => member.staff == item.staff)[0].name;
                    const station = stationData.filter(member => member.station == item.station)[0].name;
                    const formattedDate = moment(item.date).format("dddd, MMMM Do YYYY, h:mm a")
                    let elem = this.buildCard(item.session, formattedDate, staff, station, item.direction)

                    this.sessionCards.appendChild(elem)

                })
                this.buttons = this.section.querySelectorAll('.session-details');
                console.log(this.buttons);
                this.buttonListener()

            })
        })();

    }

    buttonListener() {
        this.buttons.forEach(button => {
            button.addEventListener('click', () => {
                //get the session id
                const id = button.parentNode.getAttribute('id');

                const postData = {
                    class: "StockSession",
                    method: "getSessionDetails",
                    state: true,
                    data: {
                        id: id.split('_')[1]
                    }
                }
                Server.post(postData, (data) => {

                    Section.switch('Table', data);

                })

            })
        })
    }
    buildCard(id, date, staff, station, direction) {
        let card = document.createElement('div');
        card.classList.add('card');

        const html = `<div class="card-body records">
        <h6  class="text-dark text-center">Date: ${date}</h6>
        <div class="row d-flex justify-content-around" id="session_${id}">
            <p class="lead session-staff">Staff: <span class="text-dark">${staff}</span></p>
            <p class="lead session-counter">Counter: <span class="text-dark">${station}</span></p>
            <p class="lead session-direction">Direction: <span class="text-dark">${direction}</span></p>
            <button class="btn btn-icon icon-left btn-info session-details" id=""><i class="fas fa-info-circle"></i>VIEW</button>
        </div>
    </div>`;
        card.innerHTML = html;
        return card;
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
                    }]
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
            const date = moment(formData.get("session-date")).format("YYYY-MM-DD h:mm:ss");
            const direction = formData.get("session-direction");
            const status = formData.get("session-status");

            const staffId = Storage.getFromLs('staff').filter(item => item.name == staff)[0].staff;
            const stationId = Storage.getFromLs('station').filter(item => item.name == station)[0].station;

            
            const details = {
                staff: staffId,
                station: stationId,
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
            const array_id = this.data.filter(item => item.name === this.productName.innerHTML)[0];

            tableLs.push({
                product: array_id.product,
                quantity: this.quantity.value,
            });
            Storage.saveToLs('temp-table', tableLs);
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
            }).then(ok => {
                if (ok) {
                    const session = Storage.getFromLs('session-details');

                    const postData = {
                        class: "StockSession",
                        method: "insertSessionData",
                        state: false,
                        data: {
                            session,
                            table: Storage.getFromLs('temp-table'),
                            staff: session.staff,
                            station: session.station,
                            date: session.date
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
                } else {
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
        elem.removeEventListener('click', () => {});
        elem.addEventListener('change', (e) => {

            console.log(e.target.value);

            //check if the user has registered a session
            if (this.sessionCard.classList.contains("no-display")) {
                this.data.forEach(item => {
                    if (e.target.value !== "") {
                        if (item.barcode === e.target.value) {
                            console.log(item);
                            this.currentProduct = item;
                            this.addProductItem(this.currentProduct);
                            e.target.value = '';
                            return;
                        }
                    }
                })
            } else {
                swal("You must register a session to use search");
            }
            //check if there exists a product with that barcode. in temp directory
            e.preventDefault();
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


        this.quantity.value = "";
        this.quantity.focus()
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

class Report extends Section {
    constructor() {
        super();
    }
    loadElements(section) {
        this.cards = section.querySelectorAll('.card');
        this.form = section.querySelector('#report-form');
        this.stationDropdown = section.querySelector('#station')
        console.log(this.cards);

    }
    addListeners() {

        (() => {
            const stations = Storage.getFromLs('station');

            stations.forEach(station => {
                let option = document.createElement('option');
                option.setAttribute('value', station.station);
                option.innerText = station.name;
                this.stationDropdown.appendChild(option);
            });
        })()

        this.form.addEventListener('submit', (e) => {
            const formData = new FormData(this.form);
            console.log(formData);
            const date = formData.get('date')
            const station = formData.get('station')

            const postData = {
                class: "Reports",
                method: this.type,
                state: false,
                data: {
                    date,
                    station
                }
            }
            Server.post(postData, (response) => {
                console.log(response);
            })
            e.preventDefault();
        })
        this.cards.forEach(card => {
            card.classList.add('clickable-card');
            card.addEventListener('click', () => {
                this.type = card.getAttribute('method');
                $('#modal-selector').modal({
                    backdrop: false
                });
            });
        });
    }
}

class Table extends Section {

    constructor(data) {
        super()
        this.data = data;
        this.loadData()
    }

    loadElements(section) {
        this.staff = section.querySelector('#staff-detail');
        this.station = section.querySelector('#station-detail');
        this.date = section.querySelector('#date-detail');
        this.direction = section.querySelector('#direction-detail');
        this.tbody = section.querySelector('#temp-body');
    }

    loadData() {
        // console.log(data);
        const session = this.data.session_details;
        const staffData = Storage.getFromLs('staff');
        const stationData = Storage.getFromLs('station');
        const staff = staffData.filter(member => member.staff == session.staff)[0].name;
        const station = stationData.filter(member => member.station == session.station)[0].name;
        const formattedDate = moment(session.date).format("dddd, MMMM Do YYYY, h:mm a")

        this.staff.innerText = staff
        this.station.innerText = station
        this.date.innerText = formattedDate
        this.direction.innerText = this.data.session_details.direction;

        this.buildTable(this.data.table)
    }
    addListeners() {}

    buildTable(rows) {
        rows.forEach(row => {
            let tr = document.createElement('tr');
            let productTd = document.createElement('td');
            let quantityTd = document.createElement('td');
            productTd.innerText = row.name;
            quantityTd.innerText = row.value;

            tr.appendChild(productTd)
            tr.appendChild(quantityTd)
            this.tbody.appendChild(tr);
        })
    }

}
/**
 * class ErrorPage this is a class/page that will handle all server errors and display a static error page
 */
class ErrorPage extends Section {
    constructor(code, body) {
        super();
        console.log(code);

        this.statusCode.innerHTML = code;
        this.statusMessage.innerText = body;
    }

    loadElements(section) {
        this.statusCode = section.querySelector('#status-code');
        this.statusMessage = section.querySelector('#status-message');
    }
    addListeners() {}
}

//start the application 
POS.start();