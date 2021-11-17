<?php

require_once "functions.php";

// Ladda in vår JSON data från vår fil, i detta fallet är det $users
$users = loadJson("users.json");

$companies = loadJson("companies.json");
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
    $userjson = "users.json";
    $companyjson = "companies.json";
   // Avmarkera för att testa på companies istället
   // $userjson = $companyjson;
    saveJson($userjson, $users);
    send(["id" => $id]);
}
?>