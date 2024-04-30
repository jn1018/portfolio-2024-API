project_types_arr<?php
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

// get keywords
$keywords=isset($_GET["s"]) ? $_GET["s"] : "";

// query project types
$stmt = $project_type->searchAll_WithoutPagination($keywords);
$num = $stmt->rowCount();

// check if more than 0 record found
if($num>0){

	$data="";
	$x=1;

	// retrieve our table contents
	while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
		// extract row
		// this will make $row['name'] to
		// just $name only
		extract($row);

		$data .= '{';
			$data .= '"id":'  . $id . ',';
			$data .= '"name":"'   . $name . '",';
			$data .= '"description":"'   . $description . '"';
		$data .= '}';

		$data .= $x<$num ? ',' : '';

		$x++;
	}

	// json format output
	echo "[{$data}]";
}

else{
    echo '{';
		echo '"message": "No project types found."';
	echo '}';
}
?>
