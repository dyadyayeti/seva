<?php
require_once('config.php');

class MySqlDB
{
	protected $db = null;
	
	private function dbconnect() 
	{
		$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD)
		or die ("<br/>Could not connect to MySQL server");
         
		mysqli_select_db($connection, DB_NAME)
		or die ("<br/>Could not select the indicated database");
		
		if(!$connection->set_charset("utf8")) 
		{
			printf("Ошибка при загрузке набора символов utf8: %s\n", $connection->error);
		}
     
		return $connection;
	}
	
	public function query($sql)
	{
		$result = mysqli_query($this->dbconnect(), $sql);
		
		return $result;
	}
	
	public function getDataList($sql)
	{
		$result = [];
		$query = $this->query($sql);
		
		if($query !== null)
		{	
			while($row = mysqli_fetch_array($query, MYSQLI_ASSOC))
			{
				$result[] = $row;
			}
		}
		
		return $result;
	}
	
	public function insertRow($table, $sqlData)
	{
		$result = false;
		
		if(is_array($sqlData) && !empty($sqlData) && $table != '') 
		{
			$sql = '';
			$fields = '';
			$values = '';
			$count = count($sqlData);
			$i = 0;
			
			foreach($sqlData as $field => $value) 
			{
				$i++;
				$fields.= "`".$field.(($i == $count) ? "`" : "`, ");
				$values.= "'".$value.(($i == $count) ? "'" : "', ");
			}
			
			if($fields != '' && $values != '')
			{
				$sql = 'INSERT INTO `'.$table.'` 
				(
					'.$fields.'
				) 
				VALUES 
				(
					'.$values.'
				);';
				
				$result = $this->query($sql);
			}
		}
		
		return $result;
	}
	
	public function updateRow($table, $sqlData, $id)
	{
		$result = false;
		
		if(is_array($sqlData) && !empty($sqlData) && $id > 0 && $table != '') 
		{
			$sql = '';
			$fields = '';
			$count = count($sqlData);
			$i = 0;
			
			foreach($sqlData as $field => $value) 
			{
				$i++;
				$fields.= "`".$field."` = '".$value."'".(($i == $count) ? "" : ", ");
			}
			
			if($fields != '')
			{
				$sql = "UPDATE `".$table."` 
				SET ".$fields." WHERE `id` = '".$id."';";
				
				$result = $this->query($sql);
			}
		}
		
		return $result;
	}
	
	public function deleteRow($table, $id)
	{
		$sql = "DELETE FROM `".$table."` WHERE `id` = '".$id."'";
		$result = $this->query($sql);
		
		return $result;
	}
	
	public function getOneRow($id, $table, $sqlData)
	{
		$result = [];
		
		if(is_array($sqlData) && !empty($sqlData) && $id > 0 && $table != '') 
		{
			$values = '';
			
			$count = count($sqlData);
			$i = 0;
				
			foreach($sqlData as $field => $value) 
			{
				$i++;
				$values.= "`".$value.(($i == $count) ? "`" : "`, ");
			}
			
			if($values != '')
			{
				$sql = "SELECT ".$values." 
				FROM `".$table."`
				WHERE `id` = ".$id;
				$query = $this->query($sql);
				
				if($query !== null)
				{	
					$result = mysqli_fetch_assoc($query);
				}
			}
		}
		
		return $result;
	}
	public function search($a)
	{
	          
			if(isset($a) && !empty($a))
			{
				
				
          	$ab = $this->dbconnect();
			$q= mysqli_query($ab, "SELECT id,tittle,short_text,text,status,created_at  FROM `news` WHERE short_text LIKE '%$a%'");


			$result=mysqli_fetch_assoc($q);

			echo '<h2>'.'Результат поиска'.'</h2>';
			echo '<pre>';
			print_r($result);
			echo '</pre>';	
           

		}


	}
}

 	
	
?>
