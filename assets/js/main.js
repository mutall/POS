// create an  iifee for adding event listeners when the dom has loaded
(() => {
    const storage = window.localStorage
    const list_sidebar = document.querySelector('.sidebar-menu')
    const list_items = list_sidebar.querySelectorAll('li')

    list_items.forEach(item => {
        if (item.hasAttribute('target')) {
            item.addEventListener('click', () => {
                //set the list item as active
                list_sidebar.querySelector('.active').classList.remove('active')
                item.classList.add('active')

                const activeSection = document.querySelector("section.active")
                activeSection.classList.remove('active')

                const targetSectionId = item.getAttribute('target')
                document.querySelector(`#${targetSectionId}`).classList.add('active')

            })
        }
    })



    //station data
    const stations = JSON.parse(storage.getItem('station'))
    console.log(typeof stations);

    const selectElement = document.querySelector('#stationInputState')
    stations.forEach(station => {
        let optionElem = document.createElement('option')
        optionElem.innerText = station.name.toUpperCase();
        optionElem.setAttribute('id', station.location)
        selectElement.appendChild(optionElem)
    })

    //remove default behaviour from the station form
    const stationForm = document.querySelector('#station-form')
    stationForm.addEventListener('submit', (e) => {
        e.preventDefault()
        const stockDate = document.querySelector('#stockDate').value
        const selectedStation = selectElement.options[selectElement.selectedIndex].id

        fetch('lib/chicjoint.php', {
                method: 'post',
                body: JSON.stringify({
                    class: 'Chicjoint',
                    method: 'getStock',
                    state: true,
                    date: stockDate,
                    station: selectedStation
                })
            }).then(response => response.json())
            .then(data => computate(data))
            .catch(err => console.log(err))
    })

    //set the table to a fixed 
    const table = document.querySelector('.pop-header')
    const resultBody = document.querySelector('.result-body')
    console.log(`page offset ${pageYOffset}`);
    console.log(`tbody offset ${resultBody.offsetTop}`);
    window.onscroll = () => {
        if (pageYOffset > resultBody.offsetTop) {
            console.log(`page offset ${pageYOffset}`);
            console.log(`tbody offset ${resultBody.offsetTop}`);
            table.style.display = 'block'
        } else {
            table.style.display = 'none'
            // table.classList.remove('sticky')
        }
    }
})()

const computate = (data) => {
    const tbody = document.querySelector('.result-body')
    tbody.innerHTML = ""
    data.table.forEach(item => {
        for ([key, value] of Object.entries(item)) {
            if (value == null) item[key] = 0
        }

        item.totalStock = parseInt(item.stock) + parseInt(item.added)
        item.closingStock = parseInt(item.totalStock) - parseInt(item.sale)
        item.totalAmount = parseInt(item.sale) * parseInt(item.amount)

        let row = document.createElement('tr')
        let td1 = document.createElement('td')
        let td2 = document.createElement('td')
        let td3 = document.createElement('td')
        let td4 = document.createElement('td')
        let td5 = document.createElement('td')
        let td6 = document.createElement('td')
        let td7 = document.createElement('td')
        let td8 = document.createElement('td')

        td1.innerText = item.name
        td2.innerText = item.stock
        td3.innerText = item.added
        td4.innerText = item.totalStock
        td5.innerText = item.closingStock
        td6.innerText = item.sale
        td7.innerText = item.amount
        td8.innerText = item.totalAmount

        row.appendChild(td1)
        row.appendChild(td2)
        row.appendChild(td3)
        row.appendChild(td4)
        row.appendChild(td5)
        row.appendChild(td6)
        row.appendChild(td7)
        row.appendChild(td8)


        tbody.appendChild(row)
        // console.log(item);

    })
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