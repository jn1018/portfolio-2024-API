<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object file
include_once '../config/database.php';
include_once '../objects/projects.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare project object
$project = new Project($db);

// get project id
$data = json_decode(file_get_contents("php://input"));

// set project id to be deleted
$project->id = $data->id;

// delete the project
if($project->delete()){

	// set response code - 200 ok
	http_response_code(200);
	echo json_encode(array("message" => "project was deleted."));
}

// if unable to delete the project
else{

	// set response code - 503 service unavailable
	http_response_code(503);
	echo json_encode(array("message" => "Unable to delete project."));
}
?>
