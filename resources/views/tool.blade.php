<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text-To-Speech Tool And Translator</title>
</head>
<style>
body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            text-align: center;
        }
        h2 {
            color: #696969;
            text-align: center;
        }
</style>
<body>

    <div class="container">
        <h1 id="welcome">Oobake</h1>
        <br>
        <h2 id="meaning">Meaning welcome in ga</h2>

        <div class = "conversion_form_container">
            <form id="conversion_form" method="POST" >
                @csrf
                <h2>Text to Speech Converter</h2>
                <!-- Create options to choose between voices and languages -->
                <label for="Language">Select a language</label>
                <select name="Language" id="Language" required>
                    <option value="tw">Twi</option>
                    <option value="ee">Ewe</option>
                </select>

                <label for="Speaker">Select a speaker</label>
                <select name="Speaker" id="Speaker">
         
                </select>

                <br><br>
                <textarea name="Local_text" placeholder="Type here" rows="10" cols="50"></textarea>
                <br><br>
                <button type="submit" id="convert_btn">Convert to speech</button>

                

            </form>

            <div id="Audio_trans_container">
                <h3>Audio</h3>
            </div>
            
        </div>

        <div class="translation_form_container">
             <form id="translation_form" method="POST" >
                @csrf
                <h2>Language translator</h2>
                <label for="from">From </label>
                <select name="from" id="from">
                    <option value="en">English</option>
                    <option value="tw">Twi</option>
                    <option value="ee">Ewe</option>

                </select>

                <label for="to">To</label>
                <select name="to" id="to">
                    <option value="en">English</option>
                    <option value="tw">Twi</option>
                    <option value="ee">Ewe</option>
                </select>

                <br><br>
                <textarea name="trans_text" id="trans_text" placeholder="Type here" rows="10" cols="50"></textarea>
                <br><br>
                <button type='submit' id='translate_btn'>Translate</button>
                  
               

            </form>
             <div id ='translation_container'>
                    <h3>Translated text</h3>
                    <p id="translation"></p>
                </div>
        </div>
    </div>

    @vite(['resources/js/translate.js', 'resources/js/convert.js'])
</body>
</html>