
<?php
require_once "../functions.php";
$companies = loadJson("companies.json");

$requestMethod = $_SERVER["REQUEST_METHOD"];

//Kvar att göra: 

// 1. Kunna begränsa antal entiteter vi hämtar med parametern limit=n. Denna parametern ska kunna kombineras med andra parametrar. 

// 2. Kunna inkludera relaterade entiteter med parametern include=1, t.ex. om jag haft en hund i form av { name: "Arya", owner: 1 } (där 1 är ett ID) - med denna parametern skulle vi då inkludera relationen så här { name: "Arya", owner: { name: "Sebbe" }}. Denna  av 24
// Databasbaserad publiceringHT21parameter ska kunna kombineras med andra parametrar. Det är ok om detta bara fungerar för er ena entitet.


if ($requestMethod == "GET") {

    //Get all companies
    // send($companies);

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

    //Get one compnay
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

    // Get an limit of users (not combined with other parameters)
    if (isset($_GET["limit"])) {
        $returnCompanies = array_slice($company, 0, $_GET["limit"]);
        send($returnCompanies);
    }

    
}
?>