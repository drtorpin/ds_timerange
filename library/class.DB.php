<?
class DB{
	private static $hand;
	
	private function __construct() { }
	
	public static function init() {
		if( empty(self::$hand) ) {
			self::$hand = new DB;
		}
		return self::$hand;
			
	}
	
	/** получить 1 строку из запроса в виде именованного массиа */
	public function getRow($sql) {
		$_ = &$this;
		$sql = $sql." LIMIT 1";
		$Rows = $_->getAll($sql);
		return $Rows[0];
	}
	/** получить 1 строку из запроса в виде именованного массиа */
	public function getOne($sql) {
		$_ = &$this;
		$sql = $sql." LIMIT 1";
		$Rows = $_->getAll($sql);
		if(!$Rows) return false;
		$Row = $Rows[0];
		$values = array_values($Row);
		if(!$values) return false;
		return $values[0];
	}	
	/** получить результат запроса в виде массива с указанным ключом */
	public function getAllField($sql,$field = 'id') {
		$_ = &$this;
		$STH = $_->query($sql);
		$STH->setFetchMode(PDO::FETCH_ASSOC);
		$Rows = Array();
		try {
			while($row = $STH->fetch()) {			
				$Rows[$row[$field]] = $row;
				}				
			} catch(PDOException $e) {
				echo $e->getMessage();
			}		
		return $Rows;
	}	

	/** получить результат запроса в виде массива с указанным ключом */
	public function getRowsAssoc($sql,$field = 'id',$value_key = false) {
		$_ = &$this;
		$STH = $_->query($sql);
		$STH->setFetchMode(PDO::FETCH_ASSOC);
		$Rows = Array();
		try {
			while($row = $STH->fetch()) {
				if(!$value_key) {
					foreach($row as $kk => $vv) {
						if($kk != $field) {
							$value_key = $kk;
							break;
							}
						} // foreach
					$keys = array_keys($row);					
					}
				$Rows[$row[$field]] = $row[$value_key];
				}				
			} catch(PDOException $e) {
				echo $e->getMessage();
			}		
		return $Rows;
	}	
	
	
	/** получить результат запроса в виде массива */
	public function insert($sql,$data) {
		$_ = &$this;
		$DBH = $_->connect();		
		$insert = $DBH->prepare($sql);
		$insert->execute($data);
		return $DBH->lastInsertId();		
	}
	
	/** получить результат запроса в виде массива */
	public function update($sql,$data) {
		$_ = &$this;
		$DBH = $_->connect();		
		$insert = $DBH->prepare($sql);
		$insert->execute($data);		
	}	

	/** получить результат запроса в виде массива */
	public function getAll($sql) {
		$_ = &$this;
		$STH = $_->query($sql);
		$STH->setFetchMode(PDO::FETCH_ASSOC);
		$Rows = Array();
		try {
			while($row = $STH->fetch()) {			
				$Rows[] = $row;
				}				
			} catch(PDOException $e) {
				echo $e->getMessage();
			}		
		return $Rows;
	}	
	/** выполнить запрос */
	public function query($sql) {
		$_ = &$this;
		$DBH = $_->connect();
		$STH = $DBH->query($sql);		
		return $STH; // ;
	}	
	/** подключение к базе */
	private function connect() {
		$_ = &$this;
		$host = '10.2.0.10:3306';
		$dbname = 'satro_paladin_dev_test';
		$user = 'spcom';
		$pwd = 'hG3$jhdNbe7%os';
		try {
			$dbh = new PDO("mysql:host={$host};dbname={$dbname};charset=utf8", $user, $pwd);
			$dbh->exec("SET NAMES utf8");
			return $dbh;
		} catch(PDOException $e) {
			echo $e->getMessage();
		}
	}
	
}