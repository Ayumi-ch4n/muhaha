<?php
class database {
	// databázové spojení
	private static $connection;

	// výchozí nastavení ovládače
	private static $options = array(
		PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING,
		PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
		PDO::ATTR_EMULATE_PREPARES => false
	);

	// připojení k databázi
	public static function connect($host, $dbname, $username, $password) {
		if(!isset(self::$connection)) {
			$hdbn = "mysql:host=$host;dbname=$dbname";
			self::$connection = new PDO($hdbn, $username, $password, self::$options);
		}
	}

	// návrat PDO statement-u
	private static function executeStatement($params) {
		$query = array_shift($params);
		$statement = self::$connection->prepare($query);
		$statement->execute($params);
		return $statement;
	}

	// query() - ovlivněné řádky
	public static function query($query) {
		$statement = self::executeStatement(func_get_args());
		return $statement->rowCount();
	}

	// querySingle() - první sloupec prvního řádku
	public static function querySingle($query) {
		$statement = self::executeStatement(func_get_args());
		$data = $statement->fetch();
		return $data[0];
	}

	// queryOne() - první řádek
	public static function queryOne($query) {
		$statement = self::executeStatement(func_get_args());
		return $statement->fetch(PDO::FETCH_ASSOC);
	}

	// queryAll() - všechny řádky jako pole asociativních polí
	public static function queryAll($query) {
		$statement = self::executeStatement(func_get_args());
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
}
?>
