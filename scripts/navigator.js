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

const showStock = async (identifier) =>{
    const response = await fetch("lib/stock.php", {
        method: 'POST',
        body: JSON.stringify({identifier})
    });
    
    data = await response.json();
    buildTable(data)

}

const buildTable = (data)=>{
    //build the table  to print
}