<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header('Content-Type: application/json');

// include database and object files
include_once '../config/database.php';
include_once '../objects/project-types.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare project type object
$project_type = new ProjectType($db);

// set ID property of record to read
$project_type->id = isset($_GET['id']) ? $_GET['id'] : die();

// read the details of project type to be edited
$project_type->readOne();

// create array
$project_type_arr = array(
	"id" =>  $project_type->id,
	"name" => $project_type->name,
	"description" => $project_type->description
);

// make it json format
print_r(json_encode($project_type_arr));
?>
