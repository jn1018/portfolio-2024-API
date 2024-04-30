<?php
class ProjectType {

	// DB connection var and table name
	private $conn;
	private $table_name = "project_types";

	// object properties
	public $id;
	public $name;
	public $description;

	public function __construct($db){
		$this->conn = $db;
	}

	// used to export records to csv
	public function export_CSV(){

		// Select all data
		$query = "SELECT `id`, `name`, `description` FROM " . $this->table_name;
		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		// get number of rows returned
		$num = $stmt->rowCount();

		$out = "ID,Name,Description\n";

		if($num>0){
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				//extract row
				extract($row);
				$out.="{$id},\"{$name}\",\"{$description}\"\n";
			}
		}

		return $out;
	}

	// delete selected project types
	public function deleteSelected($ids){

		$in_ids = str_repeat('?,', count($ids) - 1) . '?';

		// query to delete multiple records
		$query = "DELETE FROM " . $this->table_name . " WHERE id IN ({$in_ids})";

		$stmt = $this->conn->prepare($query);

		if($stmt->execute($ids)){
			return true;
		}else{
			return false;
		}
	}

	public function readOne(){
		// read the details of project type to be edited
		// select single record query
		$query = "SELECT name, description
				FROM " . $this->table_name . "
				WHERE id = ?
				LIMIT 0,1";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind selected record id
		$stmt->bindParam(1, $this->id);

		// execute the query
		$stmt->execute();

		// get record details
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// assign values to object properties
		$this->name = $row['name'];
		$this->description = $row['description'];
	}

	/**
	 * 
	 */
	public function update(){

		// update the project type
		$query = "UPDATE " . $this->table_name . "
				SET name = :name, description = :description
				WHERE id = :id";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->description=htmlspecialchars(strip_tags($this->description));
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind values
		$stmt->bindParam(':name', $this->name);
		$stmt->bindParam(':description', $this->description);
		$stmt->bindParam(':id', $this->id);

		// execute the query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	/**
	 * 
	 */
	public function delete(){
		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id = htmlspecialchars(strip_tags($this->id));

		// bind record id
		$stmt->bindParam(1, $this->id);

		// execute the query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	public function create(){
		// create the project type
		// insert query
		$query = "INSERT INTO project_types
				SET name = ?, description = ?, created = ?";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->description = htmlspecialchars(strip_tags($this->description));
		$this->created = htmlspecialchars(strip_tags($this->created));

		// bind values
		$stmt->bindParam(1, $this->name);
		$stmt->bindParam(2, $this->description);
		$stmt->bindParam(3, $this->created);

		// execute query
		if($stmt->execute()){
			return true;
		}

		return false;
	}

	// get search results with pagination
	public function searchPaging($search_term, $from_record_num, $records_per_page){

		// search project type based on search term
		// search query
		$query = "SELECT id, name, description
				FROM " . $this->table_name . "
				WHERE name LIKE ? OR description LIKE ?
				ORDER BY name ASC
				LIMIT ?, ?";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind variables
		$query_search_term = "%{$search_term}%";

		$stmt->bindParam(1, $query_search_term);
		$stmt->bindParam(2, $query_search_term);
		$stmt->bindParam(3, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(4, $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		return $stmt;
	}

	// count all project types
	public function count(){
		// query to count all data
		$query = "SELECT COUNT(*) as total_rows FROM project_types";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$total_rows = $row['total_rows'];

		return $total_rows;
	}

	// count all project types with search term
	public function countSearch($keywords){

		// search query
		$query = "SELECT COUNT(*) as total_rows FROM project_types WHERE name LIKE ? OR description LIKE ?";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind search term
		$keywords = "%{$keywords}%";
		$stmt->bindParam(1, $keywords);
		$stmt->bindParam(2, $keywords);

		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
		$total_rows = $row['total_rows'];

		return $total_rows;
	}

	// read all with paging
	public function readPaging($from_record_num, $records_per_page){
		// read all project types from the database
		$query = "SELECT id, name, description
				FROM " . $this->table_name . "
				ORDER BY id DESC
				LIMIT ?, ?";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind values
		$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		return $stmt;
	}

	// used by select drop-down list
	public function read(){

		//select all data
		$query = "SELECT
					id, name, description
				FROM
					" . $this->table_name . "
				ORDER BY
					name";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();

		return $stmt;
	}

	// search without pagination
	public function searchAll_WithoutPagination($keywords){
		//select all data
		$query = "SELECT
					id, name, description
				FROM
					" . $this->table_name . "
				WHERE
					name LIKE ? OR description LIKE ?
				ORDER BY
					name";

		$stmt = $this->conn->prepare( $query );

		// sanitize
		$keywords=htmlspecialchars(strip_tags($keywords));
		$keywords = "%{$keywords}%";

		// bind
		$stmt->bindParam(1, $keywords);
		$stmt->bindParam(2, $keywords);

		$stmt->execute();

		return $stmt;
	}

	// used to read project type name by its ID
	function readNameById(){

		$query = "SELECT name FROM " . $this->table_name . " WHERE id = ? limit 0,1";

		$stmt = $this->conn->prepare( $query );
		$stmt->bindParam(1, $this->id);
		$stmt->execute();

		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		$this->name = $row['name'];
	}
}
?>
