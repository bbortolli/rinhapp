<?php

mysqli_report(MYSQLI_REPORT_STRICT);

function open_database() {
	try {
		$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
		return $conn;
	} catch (Exception $e) {
		echo $e->getMessage();
		return null;
	}
}

function close_database($conn) {
	try {
		mysqli_close($conn);
	} catch (Exception $e) {
		echo $e->getMessage();
	}
}

function find( $table = null, $id = null) {
  
	$database = open_database();
	$found = null;

	try {
	  if ($id) {
		$sql = "SELECT * FROM " . $table . " WHERE _id = " . $id;
	    $result = $database->query($sql);
	    
	    if ($result->num_rows > 0) {
	      $found = $result->fetch_assoc();
	    }
	    
	  } else {
	    
		$sql = "SELECT * FROM " . $table;
		$result = $database->query($sql);
	    if ($result->num_rows > 0) {
	      $found = $result->fetch_all(MYSQLI_ASSOC);
	    }
	  }
	} catch (Exception $e) {
	  return $e->GetMessage();
  }
	
	close_database($database);
	return $found;
}

function findAll($table){
	return find($table, null);
}

function save($table = null, $data = null) {

	$database = open_database();
  
	$columns = null;
	$values = null;
  
	foreach ($data as $key => $value) {
	  $columns .= trim($key, "'") . ",";
	  $values .= "'$value',";
	}
  
	// remove a ultima virgula
	$columns = rtrim($columns, ',');
	$values = rtrim($values, ',');
	
	$sql = "INSERT INTO " . $table . "($columns)" . " VALUES " . "($values);";
  
	try {
	  $dbresponse = $database->query($sql);

	  if($dbresponse) {
		return "Data created";
	  }
	  else {
		return "Can't create data";
	  }
	
	} catch (Exception $e) { 
	  return $e->GetMessage();
	} 
  
	close_database($database);
  }

  function update($table = null, $id = 0, $data = null) {

	$database = open_database();
  
	$items = null;
  
	foreach ($data as $key => $value) {
	  $items .= trim($key, "'") . "='$value',";
	}
  
	// remove a ultima virgula
	$items = rtrim($items, ',');
  
	$sql  = "UPDATE " . $table;
	$sql .= " SET $items";
	$sql .= " WHERE id=" . $id . ";";
  
	try {
	  $dbresponse = $database->query($sql);
	  if ($dbresponse) {
		return "Data updated";
	  }
	  else {
		  return "Can't update data";
	  }
  
	} catch (Exception $e) { 
		return $e->GetMessage();
	} 
  
	close_database($database);
  }

function remove($table = null, $id = null) {

	$database = open_database();
  	  
	try {
		if($id && $table) {

			$sql = "DELETE FROM " . $table . " WHERE _id = " . $id;
			$dbresponse = $database->query($sql);
	
			if ($dbresponse) {  
				return "Remove query executed";
			}
			else {
				return "Query not executed";
			}
		}
	} catch (Exception $e) { 
		return $e->GetMessage();
	} 
  
	close_database($database);
} 

?>