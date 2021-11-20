<?php 

require_once "../functions.php";

// Ladda in vår JSON data från vår fil
$users = loadJson("users.json");
$companies = loadJson("../companies/companies.json");


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

//om firstname är lika med/mindre än 2 tecken
    // if (isset($requestData["first_name"])) {
    //     $firstName = $requestData["first_name"];

    //     if (strlen($firstName) <= 2) {
    //        send([
    //            "code" => 401,
    //            "message" => "Bad request, invalid format",
    //            "errors" => [
    //                 [
    //                     "field" => "first_name",
    //                     "message" => "`first_name` has to be more then 2 characters"
    //                 ]
    //            ]
    //         ]); 
    //     }
    // }

    $id = $requestData["id"];
    $found = false;
    $foundUser = null;
    $foundCompany = null;


    foreach ($users as $index => $user) {
        if ($user["id"] == $id) {
            $found = true;

            if (isset($requestData["first_name"])) {
                $user["first_name"] = $requestData["first_name"];
            }

            if (isset($requestData["last_name"])) {
                $user["last_name"] = $requestData["last_name"];
            }

            if (isset($requestData["gender"])) {
                $user["gender"] = $requestData["gender"];
            }

            if (isset($requestData["job_department"])) {
                $user["job_department"] = $requestData["job_department"];
            }

            if (isset($requestData["id_of_company"])) {
                $user["id_of_company"] = $requestData["id_of_company"];

                foreach ($companies as $index => $company) {
                    if ($id == $company["id"]) {
                        array_push($company["id_of_employees"], $user["id"]);

                    }

                    $companies[$index] = $company;
                    $foundCompany = $company;
                    break;
                }
            }

            // Uppdatera vår array
            $users[$index] = $user;
            $foundUser = $user;
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

    saveJson("users.json", $users);
    send($foundUser);

    saveJson("../companies/companies.json", $companies);
    send($foundCompany);

}



?>