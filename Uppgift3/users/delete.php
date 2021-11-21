<?php

require_once "../functions.php";
error_reporting(-1);
// Ladda in vår JSON data från vår fil, i detta fallet är det $users
$users = loadJson("users.json");

$companies = loadJson("../companies/companies.json");
//Avmarkera om du vill testa på companies istället
// $users = $companies;

// Vilken HTTP metod vi tog emot
$method = $_SERVER["REQUEST_METHOD"];

// Hämta ut det som skickades till vår server
$data = file_get_contents("php://input");
$requestData = json_decode($data, true);

$contentType = $_SERVER["CONTENT_TYPE"];

// Content-Type: application/json; charset=utf-8; <- ibland skickas det i detta format
if ($contentType !== "application/json") {
    send(
        ["message" => "The API only accepts JSON"],
        400
    );
}

// Tar emot { id } och sedan raderar en användare baserat på id
// Skickar tillbaka { id }
if ($method === "DELETE") {

    // Kontrollera att vi har den datan vi behöver
    if (!isset($requestData["id"])) {
        send(
            [
                "code" => 1,
                "message" => "Missing `id` of request body"
            ],
            400
        );
    }

    // Kontrollera att id är en siffra

    $id = $requestData["id"];
    $found = false;

   // Om id existerar
   foreach ($users as $index => $user) {
    if ($user["id"] == $id) {
        $found = true;
        array_splice($users, $index, 1);
        
        foreach ($companies as $index => $company) {
                if ($company["id_of_employees"] == $user["id"]) {
                    send(
                        [
                            "code" => 14,
                            "message" => "howderggreyyyyyy"
                        ],
                        200
                    );
                }
                else {
                send(
                    [
                        "code" => 44,
                        "message" => "howdyyyyyy"
                    ],
                    400
                );
            }
                 // unset($companies[array_search($id, $companies)]);
                 //unset($companies[$id]);

        }
 
        break;
    }
}


    // Om id inte existerar
    if ($found === false) {
        send(
            [
                "code" => 2,
                "message" => "The users by `id` does not exist"
            ],
            404
        );
    }

    // Uppdaterar filen
   $companyjson = "../companies/companies.json";
    $userjson = "users.json";
   // Avmarkera för att testa på companies istället
   //$userjson = $companyjson;
    saveJson($userjson, $users);
   saveJson($companyjson, $companies);
    //send(["id" => $user]);
    send ($companies);
}
?>