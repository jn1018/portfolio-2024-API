<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate project object
include_once '../objects/projects.php';

$database = new Database();
$db = $database->getConnection();

$project = new Project($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// make sure data is not empty
if(
	!empty($data->name) &&
	!empty($data->description) &&
	!empty($data->type_id)
){

	// set project property values
	$project->name = $data->name;
	$project->project_url = $data->project_url;
	$project->image_path_1 = $data->image_path_1;
	$project->image_path_2 = $data->image_path_2;
	$project->description = $data->description;
	$project->type_id = $data->type_id;
	$project->created = date('Y-m-d H:i:s');

	// create the project
	if($project->create()){

		// set response code - 201 created
		http_response_code(201);

		// tell the user
		echo json_encode(array("message" => "project was created."));
	}

	// if unable to create the project, tell the user
	else{

		// set response code - 503 service unavailable
	    http_response_code(503);
		echo json_encode(array("message" => "Unable to create project."));
	}
}

// tell the user data is incomplete
else{

	// set response code - 400 bad request
	http_response_code(400);
	echo json_encode(array("message" => "Unable to create project. Data is incomplete."));
}
?>
