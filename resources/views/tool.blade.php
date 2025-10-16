<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Text-To-Speech Tool And Translator</title>

        <script src="https://cdn.tailwindcss.com"></script>
        <script src="{{ asset('js/tabs.js')}}"></script>
        <!--@vite(['resources/css/app.css', 'resources/js/tabs.js'])-->
</head>

<body class=" bg-blue-300 min-h-screen flex flex-col items-center justify-start p-6 font-sans text-gray-700">


        <header class="text-center mb-8">
            <h1 class='text-3xl font-bold text-blue-700 mb-2' id="Title">Text To Speech Tool</h1>
            <p class="text-gray-600 italic"></p>
        </header>

        <main class="w-full max-w-6xl ">
            <!--Tab bars-->
            <div class="flex   items-center ">
              <button id="converter-tab" aria-selected="true" aria-controls="converter-panel" class="px-4 py-2 text-blue-800 hover:text-blue-400 border-2 border-blue-800 bg-white rounded-tr-lg" role="tab">Text-To-Speech</button>

              <button id="translator-tab" aria-selected="false" aria-controls="translator-panel" class="px-4 py-2 text-blue-800 border-gray-400 border-2 hover:text-blue-400 rounded-tr-lg bg-white" role="tab">Translator</button>
            </div>

            <!--Panels-->

            <div id="converter-panel" class = "conversion_form_container bg-white shadow-lg rounded-2xl rounded-tl-none p-6 border border-gray-500" role="tabpanel">
              <form id="conversion_form"  method="POST" >
                  @csrf
                  <h2 class="text-xl font-semibold text-blue-600 mb-3">Text to Speech Converter</h2>
                  <p class="text-sm text-gray-500 mb-4">Select language and speaker to convert text to speech. <span class="block text-xs text-gray-400">Note: Audio will be deleted after 30 mins.</span></p>

                  <!-- Create options to choose between voices and languages -->
                  <div class="flex gap-3 items-center mb-3">
                      <label for="Language" class=''>Select a language</label>
                      <select name="Language" id="Language" class="flex-1 p-2 rounded-lg border border-gray-300" required>
                          <option value="tw">Twi</option>
                          <option value="ee">Ewe</option>
                      </select>
                      <label for="Speaker" class="">Select speaker</label>
                      <select name="Speaker" id="Speaker" class="flex-1 p-2 rounded-lg border border-gray-300">

                      </select>
                  </div>


                  <textarea name="Local_text" id="convert_text" class="w-full h-32 border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 mb-3" placeholder="Enter up to 1000 characters...." rows="10" cols="50" maxlength="1000"></textarea>
                  <!--Set counter for characters-->
                  <div class="text-right text-sm text-gray-500 mt-1"><span id='convert_counter' class="">1000</span> characters left</div>

                  <div class="flex justify-between items-center">
                      <button type="submit" id="convert_btn" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">Convert to Audio</button>
                  </div>

                </form>

              <div id="Audio_trans_container" class="mt-4  border  p-4 rounded-lg text-center text-gray-700">
                  <h3>Audio Player Here</h3>
              </div>

            </div>

            <div id="translator-panel" class="translation_form_container bg-white shadow-lg rounded-2xl rounded-tl-none p-6 border border-gray-500" role="tabpanel" hidden>
                <form id="translation_form" method="POST" >
                    @csrf
                    <h2 class="text-xl font-semibold text-blue-600 mb-3">Language translator</h2>
                    <p class="text-sm font-semibold text-gray-500 mb-4">Select a language pair to translate to/from English</p>

                    <div class="flex gap-3 items-center mb-3">
                        <label for="from" class="">From </label>
                        <select name="from" class="flex-1 p-2 rounded-lg border border-gray-300"  id="from">
                            <option value="en">English</option>
                            <option value="tw">Twi</option>
                            <option value="ee">Ewe</option>

                        </select>

                        <label for="to">To</label>
                        <select name="to" class="flex-1 p-2 rounded-lg border border-gray-300" id="to">
                            <option value="en">English</option>
                            <option value="tw">Twi</option>
                            <option value="ee">Ewe</option>
                        </select>
                    </div>

                    <div>

                    </div>


                    <textarea name="trans_text" class="w-full h-32 border border-gray-300 rounded-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-400 mb-3" id="trans_text" placeholder="Enter up to 1000 characters...." rows="10" cols="50" maxlength="1000"></textarea>
                    <!--Set counter for characters-->
                    <div class="text-right text-sm text-gray-500 mt-1"><span id="translate_counter" class="">1000</span> characters left</div>

                    <div>
                      <button type='submit' id='translate_btn' class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">Translate</button>
                    </div>

                </form>

                <div id ='translation_container' class="mt-4  border  p-4 rounded-lg text-gray-700">
                    <p id="translation" class="">Translated text will appear here</p>
                </div>
              </div>
        </main>


    <script src="{{ asset('js/translate.js') }}"></script>
    <script src="{{ asset('js/convert.js') }}"></script>
    <!--@vite(['resources/js/translate.js', 'resources/js/convert.js'])-->
</body>
</html>
