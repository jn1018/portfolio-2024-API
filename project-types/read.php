<?php
// required header
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/project-types.php';

// instantiate database and project type object
$database = new Database();
$db = $database->getConnection();

// initialize object
$project_type = new ProjectType($db);

// query project types
$stmt = $project_type->read();
$num = $stmt->rowCount();

// check if more than zero records found
if($num>0){

	// products array
	$project_types_arr=array();
	$project_types_arr["records"]=array();

	// retrieve our table contents
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		// extract row
		// this will make $row['name'] to
		// just $name only
		extract($row);

		$project_type_item=array(
			"id" => $id,
			"name" => $name,
			"description" => html_entity_decode($description)
		);

		array_push($project_types_arr["records"], $project_type_item);
	}

	// set response code - 200 OK
    http_response_code(200);

	// show project types data in json format
	echo json_encode($project_types_arr);
}

else{

	// set response code - 404 Not found
	http_response_code(404);

	// tell the user no project types found
    echo json_encode(
		array("message" => "No project types found.")
	);
}
?>
