<?php

require_once "../functions.php";
error_reporting(-1);
// Ladda in vår JSON data från vår fil, i detta fallet är det $users
$users = loadJson("../users/users.json");

$companies = loadJson("companies.json");


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

// Tar emot { id } och sedan raderar ett företag baserat på id
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
    $foundEmployee = null;

   // Om id existerar
   foreach ($companies as $index => $company) {
   if ($company["id"] == $id) { 
       $found = true;
       array_splice($companies, $index, 1);
       break;
       }
    }

    // Kollar hur lång vår user array är
    $length = count($users);

    // Loopar igenom alla users som jobbar där
    for ($x = 0; $x <= $length; $x++) {
     foreach ($users as $index => $user) {
        if($user["id_of_company"] == $id) {
            //Raderar bort dem
            array_splice($users, $index, 1);
             break;
            } 
        }
    }

    // Om id inte existerar
    if ($found === false) {
        send(
            [
                "code" => 2,
                "message" => "The users by `id` does not exist"
            ],
            404);
    }

    // Uppdaterar filen
   $companyjson = "companies.json";
   $userjson = "../users/users.json";
   saveJson($userjson, $users);
   saveJson($companyjson, $companies);
    send(
        ["You have deleted the following company" => $company],
        200
    );
}
?>
