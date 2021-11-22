<?php
require_once "../functions.php";

// hämta metoden i servern.
$requestMethod = $_SERVER["REQUEST_METHOD"];
// hämtar typ av innehåll från servern.
$contentType = $_SERVER["CONTENT_TYPE"];

if ($contentType !== "application/json") {
    send(
        ["message" => "Bad request. You're missing 'Content-Type'."],
        400
    );
}

if ($requestMethod !== "POST") {
    send(
        ["message" => "Method not allowed. You can only use 'POST'."],
        405
    );
}

// hämtar det som skickas till vår server (allt sparas typ här).
$data = file_get_contents("php://input");
//JSON till associative array. Det man skriver i insomnia.
$requestData = json_decode($data, true);

if ($requestMethod === "POST") {
    // skapa variabler
    $id = $requestData["id"];
    $firstName = $requestData["first_name"];
    $lastName = $requestData["last_name"];
    $gender = $requestData["gender"];
    $jobDepartment = $requestData["job_department"];
    $companyID = $requestData["id_of_company"];

    // kollar om dessa nycklar är med.
    if (!isset($firstName, $lastName, $gender, $jobDepartment, $companyID)) {
        send(
            ["message" => "Bad request. There is one or more keys missing."],
            400
        );
    }

    // kollar om 'id' nyckeln är skriven.
    if (isset($id)) {
        send(
            ["message" => "The user 'id' is not allowed."],
            400
        );
    }

    if (strlen($firstName) < 3 || strlen($lastName) < 3) {
        send(
            ["message" => "Either 'firts_name' or 'last_name' has less than 3 letters."],
            400
        );
    }

    // kollar om id:et är en siffra.
    if (!is_numeric($companyID)) {
        send(
            ["message" => "The 'id' can only be a number."],
            400
        );
    }

    // dessa är associative array av datan från filerna.
    $userData = loadJson("users.json"); 
    $companyData = loadJson("../companies/companies.json"); 
    // får en array av alla företags id.
    $allCompaniesID = array_column($companyData, "id");
    
    $highestID = 0;

    foreach ($userData as $index => $user) {
        if ($user["id"] > $highestID) {
            $highestID = $user["id"];
        }
    }
    
    // den nya användaren.
    $newUser = [
        "id" => $highestID + 1,
        "first_name" => $firstName,
        "last_name" => $lastName,
        "gender" => $gender,
        "job_department" => $jobDepartment,
        "id_of_company" => $companyID
    ];

    // kollar om idet finns i companies.json, annars ett felmeddelande.
    if (!in_array($companyID, $allCompaniesID)) {
        send(
            ["message" => "The company doesn't exist. Please try again."],
            404
        );
    }

    // lägga till användaren till users.json
    array_push($userData, $newUser);

    // spara vårt innehåll
    saveJson("users.json", $userData);
    send($newUser, 201);
} 
?>