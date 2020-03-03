//speed up debbuging, declare a preset section
var activeSection = "Stock";
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
})()


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
        this.init()
        this.loadElements(this.section)
        this.addListeners()
        this.search(document.querySelector("#search-box"))
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

    search() {
        throw new UserException(`Must implement ${this.search.name} in child classes"`)
    }
    static switch(target){
        // document.querySelector('section.active').classList.remove('active')
        // document.querySelector(`#${target}`).classList.add('active')
        eval(`new ${target}()`)
    }
}

class Dashboard extends Section {
    constructor() {
        super()
    }

    loadElements(section) {}

    addListeners() {}

    search() {}
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

    //implement search method
    search(input) {
        console.log(input);
    }


}

class Stock extends Section {
    constructor() {
        super()
        this.deleteLsData()
        this.fetchClosing()


    }

    loadElements(section) {
        this.sessionForm = section.querySelector("#session-form")
        this.productName = section.querySelector("#product-name")
        this.stockQuantity = section.querySelector("#quantity")
        this.remainder = section.querySelector("#remainder")
        this.submit = section.querySelector("#btn-submit")
        // this.back = document.querySelector("")
        this.opening = section.querySelector("#stockInput")
        this.table = section.querySelector("#temp-body")
        this.image = section.querySelector("#pombe-image")
        this.form = section.querySelector('#submit-drink')
        this.date = section.querySelector('#date')
        this.upload = section.querySelector('#upload')
        this.clear = section.querySelector('#clear')
        this.sessionCard = section.querySelector("#session-card")
        this.entry = section.querySelector(".stock-entry")
    }

    addListeners() {
        this.sessionForm.addEventListener('submit', (e)=> {
            console.log(this.sessionCard)
            this.sessionCard.classList.add("no-display")
            this.entry.style.display = 'block';
            e.preventDefault();
        });
        this.form.addEventListener('submit', (e) => {
            e.preventDefault()
            const tr = document.createElement('tr');
            const tableTd = document.createElement('td')
            tableTd.innerText = this.productName.innerHTML
            const openingTd = document.createElement('td')
            openingTd.innerText = this.opening.value
            console.log(this.opening);

            tr.appendChild(tableTd)
            tr.appendChild(openingTd)
            this.table.appendChild(tr)

            //get table from local storage
            let tableLs = Storage.getFromLs('table')
            tableLs.push({
                name: this.productName.innerHTML,
                quantity: this.opening.value,
                staff: 1,
                station: 1,
                date: moment().format('YYYY/MM/DD'),
                closing: this.stockQuantity.innerText,
                sale: parseInt(this.opening.value) - parseInt(this.stockQuantity.innerText)
            })
            Storage.saveToLs('table', tableLs)

            this.data.shift()
            this.addProductItem()
            Storage.saveToLs('data', this.data)
            this.updateVals()
        })
        this.upload.addEventListener('click', () => {
            const postData = {
                class: "ChicJoint",
                method: "commitTable",
                state: false,
                data: Storage.getFromLs('table')
            }
            Server.post(postData, (data) => {
                console.log(data);

                swal({
                    title: "NICE!",
                    text: "Successfully uploaded",
                    icon: "success"
                });

                swal({
                    title: "VIEW GENERATED TABLE?",
                    icon: "info",
                    buttons: true,
                    dangerMode: true,
                })
                .then((view) => {
                    if (view) {
                        const newData = {
                            class: 'Chicjoint',
                            method: 'getStock',
                            state: true,
                            date: moment().format('YYYY/MM/DD'),
                            station: 1   
                        }
                        Server.post(newData, (data)=>{
                            Storage.saveToLs('newdata', data.table)
                            Section.switch('resulttable')
                        })
                        
                    } else {
                        swal("Ok. Continue 😀");
                    }
                });

                Storage.delete("table")
                this.updateVals()
                this.clearTable()

            })
        })
        this.clear.addEventListener('click', () => {
            swal({
                    title: "DELETE TABLE DATA?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((del) => {
                    if (del) {
                        Storage.delete('table')
                        this.updateVals()
                        this.clearTable()
                        swal("Table deleted", {
                            icon: "success",
                        });
                    } else {
                        swal("Ok. Continue 😀");
                    }
                });
        })

    }

    search() {}

    fetchClosing() {
        const postData = {
            class: "ChicJoint",
            method: "getClosingStock",
            state: true
        }
        Server.post(postData, (data) => {
            console.log(data);
            Storage.saveToLs('data', data.details)
            this.data = data.details
            this.addProductItem()
            this.updateVals()

        })
    }
    updateVals() {
        const data = Storage.getFromLs('data')
        this.remainder.innerText = `${data.length} Items remaining`

        const table = Storage.getFromLs('table')
        if (table.length > 0) {
            this.upload.style.display = 'block'
            this.clear.style.display = 'block'
        } else {
            this.upload.style.display = 'none'
            this.clear.style.display = 'none'
        }
    }

    deleteLsData() {
        Storage.delete('data')
        Storage.delete('table')
    }
    addProductItem() {
        const product = this.data[0];
        console.log(product);
        this.productName.innerHTML = product.name
        this.stockQuantity.innerHTML = product.quantity
        this.opening.value = ""
        this.date.innerText = product.date
        this.image.setAttribute('src', `../assets/images/${product.image}`)
        this.primary = product.primary;


    }
    clearTable() {
        this.table.innerHTML = ""
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
    search() {}
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
                var data = table.row( this ).data();
                // alert( 'You clicked on '+data[0]+'\'s row' );

                $.each(products, function(index, value){
                    if(value.name === data[0]){
                        productName.value = value.name
                        productBarcode.value = value.barcode
                        ProductPrice.value = value.amount
                        productPicture.setAttribute('src', `../assets/images/${value.image}`)
                    }
                })
            } );

        });
        //pproduct rows
        
        // this.ProductPrice = section.querySelector('#')


    }
    addListeners() {
        
       
    }
    search() {}
}

class resulttable extends Section{

    constructor(){
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
    loadElements(section){}
    addListeners(){}
    search(){}
}

//start the application 
POS.start()