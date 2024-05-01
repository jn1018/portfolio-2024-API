<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../config/database.php';
include_once '../objects/projects.php';

// instantiate database and project object
$database = new Database();
$db = $database->getConnection();



// initialize object
$project = new Project($db);

// query projects
$stmt = $project->read();
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num>0) {

	// projects array
	$projects_arr = array();
	$projects_arr["records"] = array();

	// retrieve our table contents
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		// extract row
		extract($row);

		$project_item=array(
			"id" => $id,
			"name" => $name,
			"description" => html_entity_decode($description),
			"project_url" => $project_url,
			"image_path_1" => $image_path_1,
			"image_path_2" => $image_path_2,
			"type_id" => $type_id,
			"type_name" => $type_name
		);

		array_push($projects_arr["records"], $project_item);
	}

	// set response code - 200 OK
    http_response_code(200);

	// show projects data in json format
	echo json_encode($projects_arr);
}

else{

	// set response code - 404 Not found
    http_response_code(404);
    echo json_encode(
		array("message" => "No projects found.")
	);
}
?>
