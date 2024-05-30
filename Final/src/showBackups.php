<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include "login_control.php";
include_once 'header.php';

$question_id = $_GET['qid'];

?>

<style>
    :root {
        --primary-color: #FF6A00;
        --primary-color-light: #FF8C42;
        --secondary-color: #000000;
        --accent-color: #FFFFFF;
        --nav-font-size: 1.2rem;
        --inactive-color: #6e6c6c;
        --invisible-color: rgba(0, 0, 0, 0);
        --body-font-family: 'Arial', sans-serif;
        --header-font-family: 'Helvetica Neue', sans-serif;
    }

    #questionText {
        font-family: var(--header-font-family);
        margin: 0 auto;
        padding: 20px;
        max-width: 800px;
        background-color: var(--primary-color-light);
        color: var(--accent-color); /* Text color for headings */
        border: 2px solid var(--primary-color); /* Updated border to use primary color */
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        font-weight: bold;
    }

    #statistics {
        font-family: var(--body-font-family);
        margin: 0 auto;
        padding: 20px;
        max-width: 800px;
    }

    #tablesContainer {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
    }

    table {
        margin-bottom: 20px;
        border-collapse: collapse;
        border: 2px solid var(--primary-color); /* Updated border to use primary color */
        width: 100%; /* Ensures tables take full width */
    }

    th, td {
        border: 1px solid var(--primary-color); /* Updated border to use primary color */
        padding: 8px;
        text-align: center;
    }

    th {
        background-color: var(--primary-color-light); /* Background color for table headers */
        color: var(--accent-color); /* Text color for table headers */
    }

    h1 {
        text-align: center;
        color: var(--secondary-color); /* Text color for headings */
    }

    .table-caption {
        caption-side: top;
        font-weight: bold;
        font-size: 1.2em;
        padding: 8px;
        background-color: var(--primary-color-light);
        color: var(--accent-color); /* Text color for table caption */
    }
</style>


<div id="statistics" >
    <h1 id="questionText"></h1>
    <div id="currentTable"></div>

    <div id="backupTables"></div>
    <div id="tablesContainer"></div>
</div>




<script>

    document.addEventListener('DOMContentLoaded', () => {
        const questionId = <?php echo json_encode($question_id); ?>;


        fetch(`controllers/getBackups.php?qid=${questionId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log(data);
                document.getElementById("questionText").innerHTML = data.question_text;

                let tablesContainer = document.getElementById("tablesContainer");

                for (let col in data.data) {
                    console.log(col);

                    const table = document.createElement('table');
                    table.innerHTML = `<caption style="caption-side: top">${col}</caption>`;

                    // Create table header
                    const headerRow = table.insertRow();
                    const th1 = document.createElement('th');
                    th1.textContent = 'Odpoveď';
                    headerRow.appendChild(th1);
                    const th2 = document.createElement('th');
                    th2.textContent = 'Počet';
                    headerRow.appendChild(th2);
                    const th3 = document.createElement('th');
                    th3.textContent = '%';
                    headerRow.appendChild(th3);

                    const dataList = data.data[col];
                    const dataListKeys = Object.keys(dataList);
                    let allCount = 0;
                    dataListKeys.forEach(key => {
                        allCount+= dataList[key];
                    });
                    

                    dataListKeys.forEach(key => {
                        const count = dataList[key];
                        const row = table.insertRow();
                        const cell1 = row.insertCell();
                        cell1.textContent = key;
                        const cell2 = row.insertCell();
                        cell2.textContent = count;
                        const cell3 = row.insertCell();
                        cell3.textContent = (count/allCount*100).toFixed(2);

                    });

                    // Append table to container
                    tablesContainer.appendChild(table);

                }


            })
            .catch(error => {
                console.error('Error fetching or processing data:', error);
            });

    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>