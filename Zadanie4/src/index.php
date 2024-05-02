<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function hash_ip($ip)
{
    return hash('sha256', $ip);
}
$response = ''; 
$servername = "localhost";
$username = "";
$password = "";
$dbname = "";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_SESSION['unique_ip'])) {
    $ip = $_SERVER['REMOTE_ADDR'];
    $ip_hash = hash_ip($ip);

    $sql = "INSERT IGNORE INTO hasovanie (ip_hash, time) VALUES (?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $ip_hash);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        $_SESSION['unique_visitors'] = isset($_SESSION['unique_visitors']) ? $_SESSION['unique_visitors'] + 1 : 1;
    }

    $stmt->close();
    $_SESSION['unique_ip'] = true;
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $city = $_POST['city'];
    $api_url = "proxy.php?city=" . urlencode($city);
    $api_response = file_get_contents($api_url);
    $weather_data = json_decode($api_response, true);
    
    $country = $weather_data['location']['country'];

    $check_sql = "SELECT mesto FROM visits WHERE mesto = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $city);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $check_stmt->close();

    if ($check_result->num_rows > 0) {
        $update_sql = "UPDATE visits SET pocet = pocet + 1 WHERE mesto = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("s", $city);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        $insert_sql = "INSERT INTO visits (mesto, stat, pocet) VALUES (?, ?, 1)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("ss", $city, $country);
        $insert_stmt->execute();
        $insert_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Weather Fetch</title>
    <link rel="stylesheet" type="text/css" href="index.css">
</head>

<body>
    <header>
        <nav>
            <div>
                <h1>Weather Fetch</h1>
            </div>
            <div>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="stats.php">Statistics</a></li>
                </ul>
            </div>

        </nav>
    </header>
    <main>
        <form id="weatherForm">
            <label for="city">Mesto</label>
            <input type="text" id="city" name="city" required>
            <input type="month" id="month" name="month">

            <button type="submit" onclick="sendToDatabase()">Zobrazit </button>
        </form>
        <div id="message"><?php echo $response; ?></div>

        <div id="weather"></div>
    </main>


        <script>
        function sendToDatabase() {
            const city = document.getElementById('city').value;
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'index.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            const formData = new FormData();
            formData.append('city', city);

            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log('Dáta úspešne odoslané do databázy.');
                } else if (xhr.status === 400) {
                    console.error('Nastala chyba pri odosielaní dát do databázy.');
                }
            };
            xhr.send(new URLSearchParams(formData));
        }
        document.addEventListener('DOMContentLoaded', function () {
    const weatherForm = document.getElementById('weatherForm');
    const weatherDiv = document.getElementById('weather');

    weatherForm.addEventListener('submit', async function (event) {
        event.preventDefault();
        const formData = new FormData(weatherForm);
        const city = formData.get('city');
        const month = formData.get('month');
        const location = encodeURIComponent(city);
        const targetMonth = month;
        const daysInMonth = new Date(targetMonth + '-01').getMonth() === 1 ? 28 : 30;

        async function fetchData(day) {
            const response = await fetch(`https://api.weatherapi.com/v1/history.json?key=a3dea48157e3485faab172744242204&q=${location}&dt=${targetMonth}-${day}`);
            const data = await response.json();
            return data.forecast.forecastday[0].day.avgtemp_c;
        }

        let totalTemperature = 0;

        for (let day = 1; day <= daysInMonth; day++) {
            const temperature = await fetchData(day);
            totalTemperature += temperature;
        }

        const averageTemperature = totalTemperature / daysInMonth;
        let conversionInfo;
        let mena, symbol; 
        console.log(city);
        fetch(`https://api.weatherapi.com/v1/current.json?key=a3dea48157e3485faab172744242204&q=${city}&aqi=no`)
            .then(response => response.json())
            .then(data => {
                const country = data.location.country;
                const country2 = data.location.country;
                const teplota = data.current.temp_c;
                const tzIdParts = data.location.tz_id.split('/');
                const cityName = tzIdParts[tzIdParts.length - 1];
                const countryParts = country.split(' ');
                const countryUrlPart = countryParts.length > 1 ? countryParts.join('-') : country;
                const flagsUrl = `https://raw.githubusercontent.com/gosquared/flags/master/src/flags/${countryUrlPart}/code`;
                const restCountriesUrl = `https://restcountries.com/v3.1/name/${country2}`;
                fetch(restCountriesUrl)
                    .then(response => response.json())
                    .then(data => {
                        const countryData = data[0];
                        const currencies = countryData.currencies;
                        const currencyCodes = Object.keys(data[0].currencies)[0];
                        console.log(currencyCodes);
                        const conversionPromises = []; 
                        for (const currencyCode in currencies) {
                            const currencyName = currencies[currencyCode].name;
                            const currencySymbol = currencies[currencyCode].symbol;
                            if (currencyName !== "EUR") {
                                const apiKey = "6568291cab2ce9072f16c364";
                                const url = `https://v6.exchangerate-api.com/v6/${apiKey}/pair/${currencyCodes}/EUR`;
                                conversionPromises.push(
                                    fetch(url)
                                        .then(response => response.json())
                                        .then(data2 => {
                                            if (data2.conversion_rate) {
                                                conversionInfo = `1 ${currencyCode} = ${data2.conversion_rate} EUR`;
                                            } else {
                                                conversionInfo = "Conversion unavailable";
                                            }
                                            mena = currencyName; 
                                            symbol = currencySymbol;
                                        })
                                        .catch(error => {
                                            console.error("Failed to fetch exchange rate:", error);
                                            conversionInfo = "Conversion unavailable";
                                            mena = currencyName; 
                                            symbol = currencySymbol;
                                        })
                                );
                                break; 
                            }
                        }
                        return Promise.all(conversionPromises);
                    })
                    .then(() => {
                        fetch(flagsUrl)
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Failed to load file content.');
                                }
                                return response.text();
                            })
                            .then(fileContent => {
                                const weatherContent = `
                                    <p>Country: ${country}</p>
                                    <p>Capital: ${cityName}</p>
                                    <p>Teplota: ${teplota}°C</p>
                                    <p>Average temperature for the month of ${targetMonth} in ${cityName} was ${averageTemperature.toFixed(2)}°C.</p>
                                    <img src="https://flagsapi.com/${fileContent}/shiny/64.png" alt="Flag of ${country}">
                                    <p>Mena: ${mena}, Symbol: ${symbol}</p>
                                    ${mena !== 'Euro' ? `<p>Conversion Info: ${conversionInfo}</p>` : ''}
                                `;
        
                                weatherDiv.innerHTML = weatherContent;
                                weatherDiv.style.display = 'block';
                            })
                            .catch(error => {
                                console.error('Error fetching file content:', error);
                            });
                    })
                    .catch(error => console.error('Error fetching data from restcountries.com:', error));
            })
            .catch(error => {
                console.error('Error fetching data:', error);
            });
    });
});

</script>


</body>

</html>