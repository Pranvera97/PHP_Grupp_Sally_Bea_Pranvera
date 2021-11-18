<?php
include_once "functions.php";

// hämta metoden i servern.
$requestMethod = $_SERVER["REQUEST_METHOD"];
// hämtar typ av innehåll från servern.
$contentType = $_SERVER["CONTENT_TYPE"];

if ($contentType !== "application/json") {
    send(
        ["message" => "Bad request."],
        400
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
    $company = $requestData["company"];

    if (!isset($firstName, $lastName, $gender, $jobDepartment, $company)) {
        send(
            ["message" => "Bad request. There are missing keys."],
            400
        );
    }

    if (isset($id)) {
        send(
            ["message" => "The user 'id' is not allowed."],
            400
        );
    }

    $userData = loadJson("users.json"); // variabel för AA av filen: users.json.
    $companyData = load("companies.json"); // variabel för AA av filen: companies.json.
    $highestID = 0;

    foreach ($userData as $index => $user) {
        if ($user["id"] > $highestID) {
            $highestID = $user["id"];
        }
    }

    // den nya användaren/anställd.
    $newUser = [
        "id" => $highestID + 1,
        "first_name" => $requestData["first_name"],
        "last_name" => $requestData["last_name"],
        "gender" => $requestData["gender"],
        "job_department" => $requestData["job_department"],
        "company" => $requestData["company"]
    ];

    // lägga till användaren till users.json
    array_push($userData, $newUser);

    // spara vårt innehåll
    saveJson("users.json", $userData);
    send($newUser, 201);
}
?>