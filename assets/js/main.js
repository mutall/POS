//create the main entrypoint for our system
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
            })
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
    static delete(key){
        this.storage.removeItem(key)
    }
}

//create a class or fetching data from server
class Server {
    static url = '../lib/chicjoint.php'
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
        this.init()
        this.loadElements()
        this.addListeners()
        console.log(typeof this.constructor.name);
    }

    init() {
        document.querySelector('section.active').classList.remove('active')
        document.querySelector(`#${this.id.toLowerCase()}`).classList.add('active')
    }

    loadElements() {
        console.log('loading elements');

    }
    addListeners() {
        console.log('attaching listeners');

    }
}

class Dashboard extends Section {
    constructor() {
        super()
    }

    loadElements() {}

    addListeners() {}
}

class Stock extends Section {
    constructor() {
        super()
    }

    loadElements() {
        this.stations = Storage.getFromLs('station')
        this.selectElement = document.querySelector('#stationInputState')
        this.stationForm = document.querySelector('#station-form')
        this.stockDate = document.querySelector('#stockDate')
        this.tbody = document.querySelector('.result-body')

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

class Bookkeeping extends Section {
    constructor() {
        super()
        this.deleteLsData()
        this.fetchClosing()
        
    
    }


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
    loadElements() {
        this.productName = document.querySelector("#product-name")
        this.stockQuantity = document.querySelector("#quantity")
        this.remainder = document.querySelector("#remainder")
        this.submit = document.querySelector("#btn-submit")
        // this.back = document.querySelector("")
        this.opening = document.querySelector("#stockInput")
        this.table = document.querySelector("#temp-body")
        this.image = document.querySelector("#pombe-image")
        this.form = document.querySelector('#submit-drink')
        this.date = document.querySelector('#date')
        this.upload = document.querySelector('#upload')
        this.clear = document.querySelector('#clear')
    }

    addListeners() {
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
                staff: "",
                station: "",
                date: ""
            })
            Storage.saveToLs('table', tableLs)

            this.data.shift()
            this.addProductItem()
            Storage.saveToLs('data', this.data)
            this.updateVals()
        })
        this.upload.addEventListener('click', ()=>{
            const postData = {
                class: "ChicJoint",
                method: "commitTable",
                state: false,
                data: Storage.getFromLs('table')
            }
            Server.post(postData, (data)=>{
                console.log(data);
                
                swal({
                    title: "NICE!",
                    text: "Successfully uploaded",
                    icon: "success"
                });
                Storage.delete("table")
                this.updateVals()
                this.clearTable()
                
            })
        })
        this.clear.addEventListener('click', ()=>{
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
    updateVals() {
        const data = Storage.getFromLs('data')
        this.remainder.innerText = `${data.length} Items remaining`
        
        const table = Storage.getFromLs('table')
        if(table.length > 0 ){
            this.upload.style.display = 'block'
            this.clear.style.display = 'block'
        }else{
            this.upload.style.display = 'none'
            this.clear.style.display = 'none'
        }
    }

    deleteLsData(){
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
    clearTable(){
        this.table.innerHTML =""
    }
}

class Setting extends Section {}


//start the application 
POS.start()