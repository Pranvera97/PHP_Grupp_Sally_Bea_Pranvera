<?php

include_once "../functions.php";
/*
$companyData = loadJson("companies.json"); 
foreach($companyData as $index => $company) {
    $array = $company["employees"]; 
    array_push($array, 5);
    echo "<pre>";
    var_dump($array);
    echo "</pre>";    
} */
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
    $companyID = $requestData["id_of_company"];

    // kollar om dessa nycklar är med.
    if (!isset($firstName, $lastName, $gender, $jobDepartment, $companyID)) {
        send(
            ["message" => "Bad request. There are missing keys."],
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

    // kollar om id:et är rätt. Nu funkar inte den med 30 bruuuuuh
    if (!$companyID > 0 && !$companyID >= 30) {
        send(
            ["The 'id' for company can only be between 1-30."],
            400
        );
    }

    // dessa är associative array av datan från filerna.
    $userData = loadJson("users.json"); 
    //$companyData = loadJson("companies.json"); 
    
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
    
    // lägga till användaren till users.json
    array_push($userData, $newUser);
    
    // lägga till användaren under företagets 'emplyoees'.
    /*
    foreach ($companyData as $index => $company) {
        if ($companyID == $company["id"]) {
            $array = $company["employees"];
            array_push($array, $newUser["id"]);
        }
    } */

    // spara vårt innehåll
    saveJson("users.json", $userData);
    //saveJson("companies.json", $companyData);
    send($newUser, 201);
} 
?>