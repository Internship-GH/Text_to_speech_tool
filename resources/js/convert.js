
//Get dropdown values from conversion form
const speakerDropdown= document.getElementById('Speaker');
const languageDropdown = document.getElementById('Language');
        
//Create an object to store speakers of each language
const speakers = {
     tw : [
        {id: 'twi_speaker_4', name : 'Male(Twi)'},
        {id: 'twi_speaker_7', name : 'Female(Twi)'}
    ],
    ee : [
        {id: 'ewe_speaker_3', name : 'Male 1(Ewe)'},
        {id: 'ewe_speaker_4', name : 'Male 2(Ewe)'}
    ]
}

//Function to update the speaker dropdown
const updateSpeakerDropdown = () => {

    const selectedLanguage = languageDropdown.value;
    const languageSpeakers = speakers[selectedLanguage];

    //Clear options in speaker dropdown
    speakerDropdown.innerHTML = '';

    //Fill dropdown menu with list of speakers
    languageSpeakers.forEach(spk => {
        const opt = document.createElement('option');
        opt.value = spk.id;
        opt.innerText = spk.name;
        speakerDropdown.appendChild(opt);
    });

}
        

//Run the update function to set the initial speakers
updateSpeakerDropdown();

//Add event listener to check for changes in language dropdown
languageDropdown.addEventListener('change', updateSpeakerDropdown);



//HANDLING CONVERSION FORM AND AUDIO DISPLAY
//Create variables for the form data and audio from api call
const convert_form = document.getElementById("conversion_form");
const audio_trans_container = document.getElementById("Audio_trans_container");
const convert_btn = document.getElementById('convert_btn');

//Create event listener for the submit button.
convert_form.addEventListener("submit", async (event) => {
    //Prevent page from refreshing
    event.preventDefault();

    //Disable submit button
    convert_btn.disabled = true;
    convert_btn.innerHTML = "Converting...";

    //Package all input fields from form
    const formData = new FormData(convert_form);

    try{
        //Send data 
        const response = await fetch('/convert', {
            method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json',
                },
        });

        //Wait for response
        const audio_data = await response.json();

        if (response.ok){
            //Empty the container
            audio_trans_container.innerHTML = "";

            //Create an audio element
            const audio = document.createElement('audio');
            audio.controls = true;
            audio.src = audio_data.audioUrl;

            //Add the audio element to the container
            audio_trans_container.appendChild(audio);
                    
            //Play audio automatically
            audio.play();

        }else {
            console.error("Error", audio_data);
        }
    }catch(error){
            console.error("Fetch Error:", error);

    }finally{
            convert_btn.disabled = false;
            convert_btn.innerHTML = "Convert to Audio";
        }
});




        
        
           
    
        