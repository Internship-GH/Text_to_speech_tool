const tabs = () => {
    const all_tabs = document.querySelectorAll('[role=tab]');
    const panels = document.querySelectorAll('[role=tabpanel]');

    all_tabs.forEach( tab => {
        //Set event listener for all both tabs over a loop
        tab.addEventListener('click', () => {
            //Reset all tabs
            all_tabs.forEach(t => {
                t.setAttribute("aria-selected", "false");
                t.classList.remove('border-blue-800', 'text-blue-800');
                t.classList.add('border-gray-400','text-gray-400')
            })

            //Hide all panels
            panels.forEach(p =>{
                p.hidden = true;
            })

            //Activate the selected tab
            tab.setAttribute("aria-selected", "true");

            tab.classList.remove('border-gray-400', 'text-gray-400');
            tab.classList.add('border-blue-800', 'text-blue-800')

            //Show the corresponding panel
            const panelId = tab.getAttribute("aria-controls");

            document.getElementById(panelId).hidden = false;

            //Change title accordingly
            const header = document.getElementById('Title');


            if(panelId == "converter-panel"){
                header.innerHTML = "Text To Speech Tool";
            }else if (panelId == "translator-panel"){
                header.innerHTML = "Translator";
            }



        });
    });

}


document.addEventListener("DOMContentLoaded", tabs);
