import axios from "axios";
 
const translation_form = document.getElementById("translation_form");
const translate_btn = document.getElementById("translate_btn");
const translation = document.getElementById('translation');

const translate = async (action) => {
    action.preventDefault();
    //Prevents page from reloading whih is the default

    //Get input directly from form
    const text_element = document.getElementById('trans_text');
    const to_element= document.getElementById('to');
    const from_element = document.getElementById('from');

    const text = text_element.value;
    const to = to_element.value;
    const from = from_element.value;

    //Disable translate button
    translate_btn.disabled = true;
    translate_btn.innerHTML = 'Translating...';

    if (to == from){
        translation.classList.add(
            "text-red-400"
        )
        translation.innerText ="Language pair not allowed";
        translate_btn.disabled = false;
        translate_btn.innerHTML = "Translate";
        return
    }else{
        translation.classList.remove(
            "text-red-400"
        )
    }

    try{
        //Make api call
        const response = await axios.post('/api/translate', {
            trans_text: text,
            from: from,
            to: to,
        },{
            headers: {
                'Accept' : 'application/json'
            }
        });

        if (response.data.success){
            translation.innerText = response.data.translated_text;
        }else{
            console.error('Translation error:', response.data.error);
            translation.innerText = 'Error:'+ response.data.error;
        }

    }catch (error){ 
        console.error('Request failed:', error);
        translation.innerText = "Request failed";
    }finally{
        translate_btn.disabled = false;
        translate_btn.innerHTML = "Translate";
    }
    
}

translation_form.addEventListener('submit', translate);

const characterCounter = (textareaId, spanId, maxChars=1000) => {
    const textarea = document.getElementById(textareaId);
    const count = document.getElementById(spanId);

    //if the text area Id or span Id cannot be found, stop running script
    if (!textareaId || !spanId){return};

    textarea.addEventListener('input', () => {
        const remaining = maxChars - textarea.value.length;
        count.textContent= remaining;
        //Let text change colour to red when it hits 100 characters or below
        if (remaining <= 100){
            count.classList.add(
                "text-red-400"
            );
        }else {
            count.classList.remove(
                "text-red-400"
            )
        }
    })
    
}

characterCounter('trans_text', 'translate_counter', 1000);