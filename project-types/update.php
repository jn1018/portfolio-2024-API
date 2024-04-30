<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object files
include_once '../config/database.php';
include_once '../objects/project-types.php';

// instantiate database and project types object
$database = new Database();
$db = $database->getConnection();

$project_type = new ProjectType($db);

// get id of project type to be edited
$data = json_decode(file_get_contents("php://input"));

// set ID property of project type to be edited
$project_type->id = $data->id;

// set project type property values
$project_type->name = $data->name;
$project_type->description = $data->description;

// execute the query
if ($project_type->update()){
	echo '{';
		echo '"message": "Project type was updated."';
	echo '}';
}

// if unable to update the project type, tell the user
else{
	echo '{';
		echo '"message": "Unable to update project type."';
	echo '}';
}
