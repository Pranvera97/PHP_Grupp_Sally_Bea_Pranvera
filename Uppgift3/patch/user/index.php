<?php 

require_once "../functions.php";

// Ladda in vår JSON data från vår fil
$users = loadJson("../users.json");

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
    $foundUser = null;

    function test() {
        foreach ($companies as $index => $company) {
            if ($requestData["company"] === $company["id"]) {
                array_push($company["employees"], $user["id"]);

            }

            $companies[$index] = $company;
            $foundCompany = $company;
            break;
        }
    }


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

            if (isset($requestData["company"])) {
                $user["company"] = $requestData["company"];

                test();
                // foreach ($companies as $index => $company) {
                //     if ($requestData["company"] === $company["id"]) {
                //         array_push($company["employees"], $user["id"]);

                //     }

                //     $companies[$index] = $company;
                //     $foundCompany = $company;
                //     break;
                // }
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

    saveJson("../users.json", $users);
    send($foundUser);

}



?>