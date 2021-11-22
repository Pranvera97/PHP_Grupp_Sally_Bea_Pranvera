<?php
require_once "../functions.php";
$users = loadJson("users.json");
$companies = loadJson("../companies/companies.json");

$requestMethod = $_SERVER["REQUEST_METHOD"];


if ($requestMethod == "GET") {

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

    // Get an limit of users (not combine with other parameters)
    if (isset($_GET["limit"])) {
        $returnUsers = array_slice($users, 0, $_GET["limit"]);
        send($returnUsers);
    }

    if (isset($GET["include"])) {
        $includeArray = [];

        foreach ($users as $key =>  $user) {
            if (in_array($user["id"], $_GET["include"])) {

                foreach ($companies as $k => $company) {
                    $includeArray[] = $users[$key];
                }
        
            }
        }
        send($includeArray);

    }


    if (isset($_GET["include"])) {
        
            foreach ($companies as $company) {
                foreach ($users as $user) {
                   if ($user["id_of_company"] == $company["id"]) {
                       
                        $user["id_of_company"] = $company["company_name"];
                        send($user);
                    } 
                    
                }
                
            }
            
    }
    
    
} else {
    //Get all users
    send($users);
}
?>