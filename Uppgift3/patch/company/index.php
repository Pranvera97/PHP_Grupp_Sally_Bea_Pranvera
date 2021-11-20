<?php 

require_once "functions.php";

// Ladda in vår JSON data från vår fil
$companies = loadJson("../companies.json");

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

    // Kontrollera att first_name och/eller last_name skickades med
    // if (!isset($requestData["first_name"]) && !isset($requestData["last_name"])) {
    //     send(
    //         [
    //             "code" => 4,
    //             "message" => "Bad request, missing `first_name` or `last_name`"
    //         ],
    //         400
    //     );
    // }

    if (isset($requestData["first_name"])) {
        $firstName = $requestData["first_name"];

        if (strlen($firstName) <= 2) {
           send([
               "code" => 401,
               "message" => "Bad request, invalid format",
               "errors" => [
                    [
                        "field" => "first_name",
                        "message" => "`first_name` has to be more then 2 characters"
                    ]
               ]
            ]); 
        }
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

            if (isset($requestData["employees"])) {
                $company["employees"] = $requestData["employees"];
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

    saveJson("../users.json", $users);
    send($foundUser);

    saveJson("../companies.json", $companies);
    send($foundCompany);
}



?>