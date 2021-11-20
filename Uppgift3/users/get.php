

<?php
require_once "../functions.php";
$users = loadJson("users.json");

$requestMethod = $_SERVER["REQUEST_METHOD"];


//Kvar att göra: 

// 1. Kunna begränsa antal entiteter vi hämtar med parametern limit=n. Denna parametern ska kunna kombineras med andra parametrar. 

// 2. Kunna inkludera relaterade entiteter med parametern include=1, t.ex. om jag haft en hund i form av { name: "Arya", owner: 1 } (där 1 är ett ID) - med denna parametern skulle vi då inkludera relationen så här { name: "Arya", owner: { name: "Sebbe" }}. Denna  av 24
// Databasbaserad publiceringHT21parameter ska kunna kombineras med andra parametrar. Det är ok om detta bara fungerar för er ena entitet.


if ($requestMethod == "GET") {

    //Get all users
    // send($users);

    // Get user by first_name
    if (isset($_GET["first_name"])) {
        $first_name = explode(",",$_GET["first_name"]);
        $first_nArray = [];
        foreach ($users as $user) {
            if (in_array($user["first_name"], $first_name)) {
                $first_nArray[] = $user;
            }
        }
        send($first_nArray);
    }

    // Get users by job_department
    if (isset($_GET["job_department"])) {
        $job_department = explode(",",$_GET["job_department"]);
        $jobArray = [];
        foreach ($users as $user) {
            if (in_array($user["job_department"], $job_department)) {
                $jobArray[] = $user;
            }
        }
        
        send($jobArray);
    }

    //Get one user
    if (isset($_GET["id"])) {
        foreach ($users as $key => $user) {
            if ($user["id"] == $_GET["id"]) {
                send($users[$key]);
            }
        }
    }

    // Get one or more
    if (isset($_GET["ids"])) {
        
        $ids = explode(",", $_GET["ids"]);
        $arrayOfUsers = [];
        foreach ($users as $user) {
            if (in_array($user["id"], $ids)) {
                $arrayOfUsers[] = $user;
            }
        }
        
        send($arrayOfUsers);
    }

    // Get an limit of users (Cannot combine with other parameters)
    if (isset($_GET["limit"])) {
        $returnUsers = array_slice($users, 0, $_GET["limit"]);
        send($returnUsers);
    }

    
}
?>