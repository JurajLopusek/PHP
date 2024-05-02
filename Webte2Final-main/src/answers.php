<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once 'header.php';

$question_id = $_GET['qid'];
?>


<h2 localize="results"></h2>
<div id="answers">
    
</div>


<div id="wordCloud">

</div>
<script>
    // JavaScript kód s použitím PHP premennej questionId
    const question_id = <?php echo json_encode($question_id); ?>;
    // Overenie, či je questionId definované a nie je null
    if (question_id) {
        const url = `controllers/showAnswers.php?qid=${question_id}`;
        function LoadAndShow() {
            document.getElementById("wordCloud").innerHTML="";
            document.getElementById("answers").innerHTML="";

            // Použitie fetch pre získanie dát zo zadaného URL
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! Status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data); // Výpis JSON dát do konzoly
                    // Príklad: Manipulácia s JSON dátami, aktualizácia UI atď.
                    let answersContainer = document.getElementById("answers");
                    const questionTextHeading = document.createElement("h3");
                    questionTextHeading.textContent = data.question_text;
                    answersContainer.appendChild(questionTextHeading);

                    // Zobrazenie údajov vo forme nečíslovaného zoznamu
                    const dataList = data.data;
                    const dataListKeys = Object.keys(dataList);

                    const dataListContainer = document.createElement("ul");
                    dataListKeys.forEach(key => {
                        const listItem = document.createElement("li");
                        const count = dataList[key];
                        const displayText = count > 1 ? `${key} (${count}x)` : key;
                        listItem.textContent = displayText;
                        dataListContainer.appendChild(listItem);
                    });

                    answersContainer.appendChild(dataListContainer);


                    createWordCloud(data);
                })
                .catch(error => {
                    console.error('Fetch error:', error);
                    // Handle errors, e.g., display an error message to the user
                });
        }
    } else {
        console.error('Question ID is not defined or null.');
        // Handle case when questionId is not defined or null
    }






    function createWordCloud(data) {
        const wordCloudContainer = document.getElementById('wordCloud'); // Získanie kontajnera pre word cloud

        // Vytvorenie poľa súboru obsahujúceho slová a ich veľkosti
        const words = Object.entries(data.data).map(([word, count]) => ({ word, count, color: getRandomColor() }));

        // Náhodné premiešanie poľa slov
        shuffle(words);

        // Vykreslenie slov z premiešaného poľa
        words.forEach(({ word, count, color }) => {
            const wordElement = document.createElement('span');
            wordElement.classList.add("cloudTag");
            wordElement.textContent = `${word} `; // Zobrazenie slova s medzerou

            // Zmena veľkosti písma podľa počtu výskytov
            wordElement.style.fontSize = `${count * 20}px`;
            wordElement.style.color = color;

            wordCloudContainer.appendChild(wordElement); // Pridanie slova do word cloudu
        });
    }

    // Funkcia na náhodné premiešanie poľa
    function shuffle(array) {
        for (let i = array.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [array[i], array[j]] = [array[j], array[i]];
        }
    }
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    LoadAndShow();
    setInterval(LoadAndShow, 5000);
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="alerts.js"></script>
<script src="script.js"></script>