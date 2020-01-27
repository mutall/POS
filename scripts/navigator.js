// get all buttons
const buttons = document.querySelectorAll("button");

// loop ove the buttons and check for a target attribute
//add click istener to move to the next section after click
buttons.forEach(btn => {
    if (btn.hasAttribute("target")) {
        btn.addEventListener("click", () => {
            let active_section = document.querySelector(".active");

            //removve active section
            active_section.classList.remove("active");

            let target = btn.getAttribute("target");
            //add the taarget as the current section
            document.querySelector(`#${target}`).classList.add("active");
        })
    }
})

const showStock = async () =>{
    const response = await fetch("lib/stock.php");
    const data = await response.json();
    buildTable(data)
    
    
    // buildTable(data)

}

const buildTable = (data)=>{
    //build the table  to print
    const tbody = document.querySelector("#table-body")
    
    
    data.forEach(item => {
        let tr = document.createElement("tr");
        
        for(const[key, value] of Object.entries(item)){
            let myTd = document.createElement('td');
            myTd.innerHTML = value
            tr.appendChild(myTd)
        }
        
        tbody.appendChild(tr)

    })
    

    showTable()
}

const showTable = () => {
    document.querySelector(".active").classList.remove("active");
    document.querySelector("#content-table").classList.add("active")
}

const openingStock = async() => {
    const response = await fetch("lib/closing_stock.php")
    const data = await response.json()
    console.log(data);

    //get access to LS
    const localStorage = window.localStorage;
    localStorage.setItem('data', JSON.stringify(data))

}
