<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// get database connection
include_once '../config/database.php';

// instantiate project type object
include_once '../objects/project-types.php';

$database = new Database();
$db = $database->getConnection();

$project_type = new ProjectType($db);

// get posted data
$data = json_decode(file_get_contents("php://input"));

// set project type property values
$project_type->name = $data->name;
$project_type->description = $data->description;
$project_type->created = date('Y-m-d H:i:s');

// create the project type
if($project_type->create()){
	echo '{';
		echo '"message": "Project type was created."';
	echo '}';
}

// if unable to create the project type, tell the user
else{
	echo '{';
		echo '"message": "Unable to create project type."';
	echo '}';
}
?>
