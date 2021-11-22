<?php
require_once "../functions.php";
$users = loadJson("users.json");
$companies = loadJson("../companies/companies.json");

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod != "GET") {
    send(
        ["message" => "Method not allowed. Only 'GET' works."],
        405
    );
}

if ($requestMethod == "GET") {
    // skapa varibler
    $firstName = $_GET["first_name"];
    $lastName = $_GET["last_name"];
    $gender = $_GET["gender"];
    $jobDep = $_GET["job_department"];
    $companyId = $_GET["id_of_company"];
    $limit = $_GET["limit"];

    // 'limit' tillsammans med andra parametrar.
    if (isset($limit)) {
        $newArray = [];
        
        // Gender
        if (isset($gender)) {
            foreach ($users as $index => $user) {
                if ($user["gender"] == $gender) {
                array_push($newArray, $user);
                }  
            }

            $limitedUsers = array_slice($newArray, 0, $limit);
            send($limitedUsers);                
        }
        
        // Job department
        if (isset($jobDep)) {
            foreach ($users as $index => $user) {
                if ($user["job_department"] == $jobDep) {
                array_push($newArray, $user);
                }  
            }

            $limitedUsers = array_slice($newArray, 0, $limit);
            send($limitedUsers);                
        }

        // Company ID
        if (isset($companyId)) {
            foreach ($users as $index => $user) {
                if ($user["id_of_company"] == $companyId) {
                array_push($newArray, $user);
                }  
            }

            $limitedUsers = array_slice($newArray, 0, $limit);
            send($limitedUsers);                
        }

        $returnUsers = array_slice($users, 0, $limit);
        send($returnUsers);
    }

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

    //Får fram personens företag beroende på vilket id som anges i include URL
    if (isset($_GET["include"])) {
        $include = $_GET["include"];
        foreach ($users as $user) {
            if($include == $user["id_of_company"]){
                $userCompany = $user["id_of_company"];
                $found = false;

                //loopar igenom företag och jämför angett id för att få fram rätt företag
                foreach($companies as $company) {

                    if($company["id"] == $userCompany) {  
                      $found = true;
                      //Byter ut siffra til namnet på företaget
                      $user["id_of_company"] = $company["company_name"];
                        
                        send([
                            $user
                            ],
                            200);
                    } 
                } 
            }
        //om användaren anger ett ID som inte finns så får de upp följande felmeddelande
        }if($include !== $user["id_of_company"]) {
            send([
                "code" => 4,
                "Message" => "ID does not exist"],
                404
            );
            exit();
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

    //Get all users
    send($users);
}
?>