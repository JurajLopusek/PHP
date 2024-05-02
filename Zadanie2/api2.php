<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $pracovisko = $_GET['pracovisko'] ?? null;
    $typprace = $_GET['typprace'] ?? null;
    $data = getData($pracovisko, $typprace);
    echo json_encode($data);
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Neplatný požadavek']);
}
function getData($pracovisko, $typprace)
{
    $ch = curl_init();
    $url = "https://is.stuba.sk/pracoviste/prehled_temat.pl?lang=sk;pracoviste=" . $pracovisko;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    if ($response === false) {
        return ['error' => 'Chyba pri vykonávaní požiadavky'];
    } else {
        $dom = new DOMDocument();
        @$dom->loadHTML($response);
        $xpath = new DOMXPath($dom);
        $tables = $xpath->query('//table');
        if ($tables->length > 0) {
            $firstTable = $tables->item(3);
            $data = [];
            foreach ($firstTable->getElementsByTagName('tr') as $row) {
                $rowData = [];
                foreach ($row->getElementsByTagName('td') as $cell) {
                    $rowData[] = $cell->nodeValue;
                }

                $cells = $row->getElementsByTagName('td');
                if ($cells->length > 8) { 
                    $cell = $cells->item(8); 
                    $anchors = $cell->getElementsByTagName('a');
                   
                    if ($anchors->length > 0) {
                        $anchorHref = $anchors->item(0)->getAttribute('href');
                        
                        preg_match('/detail=(\d+)/', $anchorHref, $matches);
                        $id = isset($matches[1]) ? $matches[1] : null;
                    }
                    $parts = explode('/', $rowData[9]);
                    $obsadenost = $parts[0];
                    $maxKapacita = $parts[1];
                    if ($typprace == 0) {
                        if ($obsadenost < $maxKapacita || $maxKapacita == " --") {
                            $rowData[8] = $id;
                            $data[] = $rowData;
                            
                        }
                    } else {
                        $typeIndex = 1;
                        if (($typprace === '1' && isset($rowData[$typeIndex]) && $rowData[$typeIndex] === 'BP') && ($obsadenost < $maxKapacita || $maxKapacita == " --")) {
                            $rowData[8] = $id;
                            $data[] = $rowData;
                            
                        } elseif (($typprace === '2' && isset($rowData[$typeIndex]) && $rowData[$typeIndex] === 'DP') && ($obsadenost < $maxKapacita || $maxKapacita == " --")) {
                            $rowData[8] = $id;
                            $data[] = $rowData;
                            
                        } elseif (($typprace === '3' && isset($rowData[$typeIndex]) && $rowData[$typeIndex] === 'DizP') && ($obsadenost < $maxKapacita || $maxKapacita == " --")) {
                            $rowData[8] = $id;
                            $data[] = $rowData;
                            
                        }
                    }

                }


            }
            return $data;
        } else {
            return ['error' => 'Tabuľka nebola nájdená'];
        }
    }
}
?>