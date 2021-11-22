<?php 

require_once "../functions.php";

// Ladda in vår JSON data från vår fil
$companies = loadJson("companies.json");

// Vilken HTTP metod vi tog emot
$method = $_SERVER["REQUEST_METHOD"];

// Hämta ut det som skickades till vår server
$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

if ($method != "PATCH") {
    send(
        ["message" => "Method not allowed. Only 'PATCH' works."],
        405
    );
}

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

                //om company_name är = 0 tecken
                if (strlen($requestData["company_name"]) == 0) {
                    send([
                        "code" => 401,
                        "message" => "Bad request, invalid format",
                        "errors" => [
                                [
                                    "field" => "company_name",
                                    "message" => "`company_name` has to be more then 0 characters"
                                ]
                        ]
                    ]); 
                }

                $company["company_name"] = $requestData["company_name"];
            }

            if (isset($requestData["country"])) {

                //om country är = 0 tecken
                if (strlen($requestData["country"]) == 0) {
                    send([
                        "code" => 401,
                        "message" => "Bad request, invalid format",
                        "errors" => [
                                [
                                    "field" => "country",
                                    "message" => "`country` has to be more then 0 characters"
                                ]
                        ]
                    ]); 
                }

                $company["country"] = $requestData["country"];
            }

            if (isset($requestData["address"])) {

                //om address är = 0 tecken
                if (strlen($requestData["address"]) == 0) {
                    send([
                        "code" => 401,
                        "message" => "Bad request, invalid format",
                        "errors" => [
                                [
                                    "field" => "address",
                                    "message" => "`address` has to be more then 0 characters"
                                ]
                        ]
                    ]); 
                }

                $company["address"] = $requestData["address"];
            }

            if (isset($requestData["phone_number"])) {

                //om phone_number är = 0 tecken
                if (strlen($requestData["phone_number"]) == 0) {
                    send([
                        "code" => 401,
                        "message" => "Bad request, invalid format",
                        "errors" => [
                                [
                                    "field" => "phone_number",
                                    "message" => "`phone_number` has to be more then 0 characters"
                                ]
                        ]
                    ]); 
                }

                $company["phone_number"] = $requestData["phone_number"];
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