<?php 

require_once "../functions.php";

// Ladda in vår JSON data från vår fil
$companies = loadJson("companies.json");

// Vilken HTTP metod vi tog emot
$method = $_SERVER["REQUEST_METHOD"];

// Hämta ut det som skickades till vår server
$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

if ($method === "PATCH") {
    // Kontrollera att vi har den datan vi behöver
    if (!isset($requestData["id"])) {
        send(
            [
                "code" => 3,
                "message" => "Missing `id` of request body"
            ],
            400
        );
    }

    $id = $requestData["id"];
    $found = false;
    $foundCompany = null;


    foreach ($companies as $index => $company) {
        if ($company["id"] == $id) {
            $found = true;

            if (isset($requestData["company_name"])) {
                $company["company_name"] = $requestData["company_name"];
            }

            if (isset($requestData["country"])) {
                $company["country"] = $requestData["country"];
            }

            if (isset($requestData["address"])) {
                $company["address"] = $requestData["address"];
            }

            if (isset($requestData["phone_number"])) {
                $company["phone_number"] = $requestData["phone_number"];
            }

            if (isset($requestData["id_of_employees"])) {
                // $company["id_of_employees"] = $requestData["id_of_employees"];

                // array_push($company["id_of_employees"], $requestData["id_of_employees"]);
            }

            $companies[$index] = $company;
            $foundCompany = $company;
            
            break;
        }
    }


    if ($found === false) {
        send(
            [
                "code" => 5,
                "message" => "The users by `id` does not exist"
            ],
            404
        );
    }

    saveJson("companies.json", $companies);
    send($foundCompany);
}



?>