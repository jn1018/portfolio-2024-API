<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/core.php';
include_once '../shared/utilities.php';
include_once '../config/database.php';
include_once '../objects/projects.php';

// utilities
$utilities = new Utilities();

// instantiate database and project object
$database = new Database();
$db = $database->getConnection();

// initialize object
$project = new Project($db);

// query projects
$stmt = $project->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// check if more than 0 record found
if ($num>0) {

	// projects array
	$projects_arr=array();
	$projects_arr["records"]=array();
	$projects_arr["paging"]=array();

	// retrieve our table contents
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		// extract row
		extract($row);

		$project_item = array(
			"id" => $id,
			"name" => $name,
			"description" => html_entity_decode($description),
			"project_url" => $project_url,
			"type_id" => $type_id,
			"type_name" => $type_name
		);

		array_push($projects_arr["records"], $project_item);
	}


	// include paging
	$total_rows = $project->count();
	$page_url = "{$home_url}project/read_paging.php?";
	$paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
	$projects_arr["paging"]=$paging;

	// set response code - 200 OK
	http_response_code(200);

	// make it json format
	echo json_encode($projects_arr);
}

else{

	// set response code - 404 Not found
	http_response_code(404);

	// tell the user projects does not exist
    echo json_encode(
		array("message" => "No projects found.")
	);
}
?>
