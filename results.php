<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Searcher avoiding sql injection</title>
</head>
<body>
<?php
$country = $_GET["search"];

require("connection_variables.php");

$connection = mysqli_connect($db_host, $db_user, $db_pass);

if(mysqli_connect_errno()) {
    echo "Failed to connect to DB";
    exit();
}

mysqli_select_db($connection, $db_name) or die("DB not found!");

mysqli_set_charset($connection, "utf8");

$sql = "SELECT CÓDIGOARTÍCULO, SECCIÓN, PRECIO, PAÍSDEORIGEN FROM PRODUCTOS WHERE PAÍSDEORIGEN= ?";

$result = mysqli_prepare($connection, $sql);

//this function returns boolean, and has 3params: 'mysqli_stmt' object returned by mysqli_prepare, type of the query ("s" for string), and a variable containing the query:
$ok = mysqli_stmt_bind_param($result, "s", $country);

//finally, execute the query; also returns boolean:
$ok = mysqli_stmt_execute($result);


if(!$ok) {
    echo "Error executing query";
} else {
    //if query was successful, let's take each result and print them:
        
    // first parameter is all the found products, rest are a variable for every field of each product:
    $ok = mysqli_stmt_bind_result($result, $code, $section, $price, $country);

    //read the results:
    echo "Found products: <br><br>";

    //fetch to get the results, and loop over each result:
    while(mysqli_stmt_fetch($result)) {
        //concate the returned fields, for every product:
        echo $code . " " . $section . " " . $price . " " . $country . "<br>";
    }

    //and close the object result:
    mysqli_stmt_close($result);

}

?>
    
</body>
</html>
