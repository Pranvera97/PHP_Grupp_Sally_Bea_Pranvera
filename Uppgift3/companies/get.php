<?php
require_once "../functions.php";
$companies = loadJson("companies.json");
$employees = loadJson("../users/users.json");

$requestMethod = $_SERVER["REQUEST_METHOD"];

if ($requestMethod != "GET") {
    send(
        ["message" => "Method not allowed. Only 'GET' works."],
        405
    );
}

if ($requestMethod == "GET") {
    // 'limit' tillsammans med parametern 'country'.
    if (isset($_GET["limit"], $_GET["country"])) {
        $arrayOfObj = [];

        foreach ($companies as $index => $company) {
            if ($company["country"] == $_GET["country"]) {
                array_push($arrayOfObj, $company);
            }  
        }

        $limitedEntities = array_slice($arrayOfObj, 0, $_GET["limit"]);

        send($limitedEntities);
    }

    // Get company by company_name
    if (isset($_GET["company_name"])) {
        $company_name = explode(",",$_GET["company_name"]);
        $companyArray = [];
        foreach ($companies as $company) {
            if (in_array($company["company_name"], $company_name)) {
                $companyArray[] = $company;
            }
        }
        send($companyArray);
    }

    //Get one company
    if (isset($_GET["id"])) {
        foreach ($companies as $key => $company) {
            if ($company["id"] == $_GET["id"]) {
                send($companies[$key]);
            }
        }
    }

    // Get one or more
    if (isset($_GET["ids"])) {
        $ids = explode(",", $_GET["ids"]);
        $arrayOfC = [];
        foreach ($companies as $company) {
            if (in_array($company["id"], $ids)) {
                $arrayOfC[] = $company;
            }
        }
        
        send($arrayOfC);
    }

    // get all the companies with the same country.
    if (isset($_GET["country"])) {
        $companyByCountry = [];
        foreach ($companies as $index => $company) {
            if ($company["country"] == $_GET["country"]) {
                array_push($companyByCountry, $company);
            }
        }
        
        send($companyByCountry);
    }

    // Get an limit of users (not combined with other parameters)
    if (isset($_GET["limit"])) {
        $limitedCompanies = array_slice($companies, 0, $_GET["limit"]);

        send($limitedCompanies);
    }

    //Get all companies
    send($companies);
}
?>