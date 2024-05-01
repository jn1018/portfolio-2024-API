<?php
class Project {

	// database connection and table name
	private $conn;
	private $table_name = "projects";

	// object properties
	public $id;
	public $name;
	public $description;
	public $project_url;
	public $image_path_1;
	public $image_path_2;
	public $type_id;
	public $type_name;
	public $created;

	// constructor with $db as database connection
	public function __construct($db){
		$this->conn = $db;
	}

	// used to export records to csv
	public function export_CSV(){

		//select all data
		$query = "SELECT id, name, description, project_url, image_path_1, image_path_2, created, modified FROM projects";
		$stmt = $this->conn->prepare($query);
		$stmt->execute();

		//this is how to get number of rows returned
		$num = $stmt->rowCount();

		$out = "ID,Name,Description,ProjectUrl,ImagePath1,ImagePath2,Created,Modified\n";

		if($num>0){
			//retrieve our table contents
			//fetch() is faster than fetchAll()
			//http://stackoverflow.com/questions/2770630/pdofetchall-vs-pdofetch-in-a-loop
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				//extract row
				//this will make $row['name'] to
				//just $name only
				extract($row);
				$out.="{$id},\"{$name}\",\"{$description}\",\"{$project_url}\",\"{$image_path_1}\",\"{$image_path_2}\",{$created},{$modified}\n";
			}
		}

		return $out;
	}

	// used for paging projects
	public function count(){
		$query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

		$stmt = $this->conn->prepare( $query );
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['total_rows'];
	}

	// used for paging projects
	public function countSearch($keywords){

		$query = "SELECT COUNT(*) as total_rows
					FROM
						" . $this->table_name . " p
						LEFT JOIN categories c
							ON p.type_id = c.id
					WHERE p.name LIKE ? OR p.description LIKE ? OR c.name LIKE ?";

		$stmt = $this->conn->prepare( $query );

		// sanitize
		$keywords=htmlspecialchars(strip_tags($keywords));
		$keywords = "%{$keywords}%";

		// bind variable values
		$stmt->bindParam(1, $keywords);
		$stmt->bindParam(2, $keywords);
		$stmt->bindParam(3, $keywords);

		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		return $row['total_rows'];
	}

	// create product
	function create(){

		// query to insert record
		$query = "INSERT INTO
					" . $this->table_name . "
				SET
					name=:name, project_url=:project_url, description=:description, image_path_1=:image_path_1, image_path_2=:image_path_2, type_id=:type_id, created=:created";

		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->name = htmlspecialchars(strip_tags($this->name));
		$this->project_url = htmlspecialchars(strip_tags($this->project_url));
		$this->image_path_1 = htmlspecialchars(strip_tags($this->image_path_1));
		$this->image_path_2 = htmlspecialchars(strip_tags($this->image_path_2));
		$this->description = htmlspecialchars(strip_tags($this->description));
		$this->type_id = htmlspecialchars(strip_tags($this->type_id));
		$this->created = htmlspecialchars(strip_tags($this->created));

		// bind values
		$stmt->bindParam(":name", $this->name);
		$stmt->bindParam(":project_url", $this->project_url);
		$stmt->bindParam(":image_path_1", $this->image_path_1);
		$stmt->bindParam(":image_path_2", $this->image_path_2);
		$stmt->bindParam(":description", $this->description);
		$stmt->bindParam(":type_id", $this->type_id);
		$stmt->bindParam(":created", $this->created);

		// execute query
		if($stmt->execute()){
			return true;
		}else{
			echo "<pre>";
				print_r($stmt->errorInfo());
			echo "</pre>";

			return false;
		}
	}

	// read projects
	public function read(){

		// select all query
		$query = "SELECT
					t.name as type_name, p.id, p.name, p.description, p.project_url, p.image_path_1, p.image_path_2, p.type_id, p.created
				FROM
					" . $this->table_name . " p
					LEFT JOIN
						project_types t
							ON p.type_id = t.id
				ORDER BY
					p.created DESC";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// execute query
		$stmt->execute();

		return $stmt;
	}

	// search projects with pagination
	function searchPaging($keywords, $from_record_num, $records_per_page){

		// select all query
		$query = "SELECT
					t.name as type_name, p.id, p.name, p.description, p.project_url, p.image_path_1, p.image_path_2, p.type_id, p.created
				FROM
					" . $this->table_name . " p
					LEFT JOIN project_types t
						ON p.type_id = t.id
				WHERE p.name LIKE ? OR p.description LIKE ? OR t.name LIKE ?
				ORDER BY p.created DESC
				LIMIT ?, ?";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$keywords=htmlspecialchars(strip_tags($keywords));
		$keywords = "%{$keywords}%";

		// bind variable values
		$stmt->bindParam(1, $keywords);
		$stmt->bindParam(2, $keywords);
		$stmt->bindParam(3, $keywords);
		$stmt->bindParam(4, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(5, $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		return $stmt;
	}

	// search projects
	function search($keywords){

		// select all query
		$query = "SELECT
					t.name as type_name, p.id, p.name, p.description, p.project_url, p.image_path_1, p.image_path_2, p.type_id, p.created
				FROM
					" . $this->table_name . " p
					LEFT JOIN
						project_types t
							ON p.type_id = t.id
				WHERE
					p.name LIKE ? OR p.description LIKE ? OR t.name LIKE ?
				ORDER BY
					p.created DESC";
		echo $query;
		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$keywords=htmlspecialchars(strip_tags($keywords));
		$keywords = "%{$keywords}%";

		// bind
		$stmt->bindParam(1, $keywords);
		$stmt->bindParam(2, $keywords);
		$stmt->bindParam(3, $keywords);

		// execute query
		$stmt->execute();

		return $stmt;
	}

	// read projects
	function readAllProjectsByCategoryId(){

		// select all query
		$query = "SELECT
					t.name as type_name, p.id, p.name, p.description, p.project_url, p.image_path_1, p.image_path_2, p.type_id, p.created
				FROM
					" . $this->table_name . " p
					LEFT JOIN
						project_types t
							ON p.type_id = t.id
				WHERE
					p.type_id = ?
				ORDER BY
					p.created DESC";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// bind id of product to be updated
		$stmt->bindParam(1, $this->type_id);

		// execute query
		$stmt->execute();

		return $stmt;
	}

	// used when filling up the update project form
	function readOne(){

		// query to read single record
		$query = "SELECT
					t.name as type_name, p.id, p.name, p.description, p.project_url, p.image_path_1, p.image_path_2, p.type_id, p.created
				FROM
					" . $this->table_name . " p
					LEFT JOIN
						project_types t
							ON p.type_id = t.id
				WHERE
					p.id = ?
				LIMIT
					0,1";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind id of product to be updated
		$stmt->bindParam(1, $this->id);

		// execute query
		$stmt->execute();

		// get retrieved row
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		// set values to object properties
		$this->name = $row['name'];
		$this->project_url = $row['project_url'];
		$this->image_path_1 = $row['image_path_1'];
		$this->image_path_2 = $row['image_path_2'];
		$this->description = $row['description'];
		$this->type_id = $row['type_id'];
		$this->type_name = $row['type_name'];
	}

	// read projects with pagination
	public function readPaging($from_record_num, $records_per_page){

		// select query
		$query = "SELECT
					t.name as type_name, p.id, p.name, p.description, p.project_url, p.image_path_1, p.image_path_2, p.type_id, p.created
				FROM
					" . $this->table_name . " p
					LEFT JOIN
						project_types t
							ON p.type_id = t.id
				ORDER BY p.created DESC
				LIMIT ?, ?";

		// prepare query statement
		$stmt = $this->conn->prepare( $query );

		// bind variable values
		$stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
		$stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

		// execute query
		$stmt->execute();

		// return values from database
		return $stmt;
	}

	// update the project
	function update(){

		// update query
		$query = "UPDATE
					" . $this->table_name . "
				SET
					name = :name,
					project_url = :project_url,
					image_path_1 = :image_path_1,
					image_path_2 = :image_path_2,
					description = :description,
					type_id = :type_id
				WHERE
					id = :id";

		// prepare query statement
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->name=htmlspecialchars(strip_tags($this->name));
		$this->project_url=htmlspecialchars(strip_tags($this->project_url));
		$this->image_path_1=htmlspecialchars(strip_tags($this->image_path_1));
		$this->image_path_2=htmlspecialchars(strip_tags($this->image_path_2));
		$this->description=htmlspecialchars(strip_tags($this->description));
		$this->type_id=htmlspecialchars(strip_tags($this->type_id));
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind new values
		$stmt->bindParam(':name', $this->name);
		$stmt->bindParam(':project_url', $this->project_url);
		$stmt->bindParam(':image_path_1', $this->image_path_1);
		$stmt->bindParam(':image_path_2', $this->image_path_2);
		$stmt->bindParam(':description', $this->description);
		$stmt->bindParam(':type_id', $this->type_id);
		$stmt->bindParam(':id', $this->id);

		// execute the query
		if ($stmt->execute()) {
			return true;
		} else {
			return false;
		}
	}

	// delete the project
	function delete(){

		// delete query
		$query = "DELETE FROM " . $this->table_name . " WHERE id = ?";

		// prepare query
		$stmt = $this->conn->prepare($query);

		// sanitize
		$this->id=htmlspecialchars(strip_tags($this->id));

		// bind id of record to delete
		$stmt->bindParam(1, $this->id);

		// execute query
		if ($stmt->execute()) {
			return true;
		}

		return false;

	}

	// delete selected projects
	public function deleteSelected($ids){

		$in_ids = str_repeat('?,', count($ids) - 1) . '?';

		// query to delete multiple records
		$query = "DELETE FROM " . $this->table_name . " WHERE id IN ({$in_ids})";

		$stmt = $this->conn->prepare($query);

		if ($stmt->execute($ids)) {
			return true;
		} else {
			return false;
		}
	}

}
?>
