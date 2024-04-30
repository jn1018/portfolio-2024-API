<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// include database and object file
include_once '../config/database.php';
include_once '../objects/project-types.php';

// get database connection
$database = new Database();
$db = $database->getConnection();

// prepare project type object
$project_type = new ProjectType($db);

// get project type id
$data = json_decode(file_get_contents("php://input"));

// set project type id to be deleted
$project_type->id = $data->id;

// delete the project type
if($project_type->delete()){
	echo '{';
		echo '"message": "Product was deleted."';
	echo '}';
}

// if unable to delete the project type
else {
	echo '{';
		echo '"message": "Unable to delete object."';
	echo '}';
}
?>
