<?
/**
 * -- Implementación de Flight Framework --
 * -- An extensible micro-framework for PHP --
 * 
 * @author Ivan A. Fretes
 * @link http://flightphp.com/ 
 * 
 */
require 'flight/Flight.php';


/**
 * Atributos de conexion a la base de datos, 
 */

define("DBMANAGER", "mysql");
define("URL_WEBSERVICE", "/");
define("HOSTNAME","localhost");
define("DBNAME","dbname");
define("HOSTUSER","root");
define("HOSTPASS", "123456");
	

/**
 * Registramos atributo db como propiedad de Flight
 */
Flight::register('db', 'PDO', array( 
								DBMANAGER.':host='.HOSTNAME.';dbname='.DBNAME ,
								HOSTUSER, HOSTPASS ), 
	function($db){
	    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$db->exec("set names utf8");
	}
);




/**
 * Listado de registros
 * @example  ~/facturacion
 * 
 * Lista un registro
 * @example  ~/facturacion/facturaid/265
 * 
 * @param number @table : Nombre de la tabla
 * @param string $field_name _ Nombre del campo a relacionar
 * @param mixed $field_value : Valor buscado en el $field_name
 */
Flight::route("GET /@table(/@field_name(/@field_value))", 
	function($table, $field_name, $field_value) {

	if (Flight::verifyNull($table)){


		$db = Flight::db();
		$query = " SELECT * FROM $table ";

		// Si los datos del registro estan inicializados
		if (is_string($field_name) && isset($field_value)){
			$query = " WHERE $field_name = $field_value "; 
		}

		// Desde y cantidad de registro a visualizar
		$query .= Flight::limitRow(Flight::request()->query['init'],
								   Flight::request()->query['cant']);

  
	    $stmt = $db->prepare($query);
	    $stmt->execute();

	    Flight::headerData();
		echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS));

		$db = null;	 
	}
	

});


/**
 * Listado de registros
 * @example  ~/facturacion
 * 
 * Lista un registro
 * @example  ~/facturacion/facturaid/265
 * 
 * @param number @table : Nombre de la tabla
 * @param string $field_name _ Nombre del campo a relacionar
 * @param mixed $field_value : Valor buscado en el $field_name
 */
Flight::route("GET /@table(/@field_name(/@field_value))", 
	function($table, $field_name, $field_value) {

	if (Flight::verifyNull($table)){


		$db = Flight::db();
		$query = " SELECT * FROM $table ";

		// Si los datos del registro estan inicializados
		if (is_string($field_name) && isset($field_value)){
			$query = " WHERE $field_name = $field_value "; 
		}

		// Desde y cantidad de registro a visualizar
		$query .= Flight::limitRow(Flight::request()->query['init'],
								   Flight::request()->query['cant']);

  
	    $stmt = $db->prepare($query);
	    $stmt->execute();

	    Flight::headerData();
		echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS));

		$db = null;	 
	}
	

});


/**
 * Generación de Index del webservice
 */
Flight::route('/', function(){
	echo "hello word. Webservice made with Flight";
});




/**
 * Verifica todos los posibles valores, nulos
 * 0, NULL, FALSE , 
 */
Flight::map('verifyNull', function($word){
	if ('' === trim($word) || NULL === $word || 
		0 == $word){
		return TRUE;
	}

	return FALSE;
});


/**
 * Genera el limite y la cantidad de registros 
 * 
 * 
 * @param number $init : Registro Inicial
 * @param number $limit : Cantidad de registros
 * 
 */
Flight::map('limitRow', function($init, $cant){

	if (NULL === $init)	$init = 0;
	if (NULL === $cant) $cant = 25;

	try {
		
		if (!is_numeric($init) || !is_numeric($cant))
			throw new Exception("Valores del init o cant son incorrectos");
			  
		return " LIMIT $init, $cant ";

	} catch (Exception $e) {
		echo $e->getMessage();
	}
});



/**
 * Verificamos la procedencia de los accesos
 */
Flight::map('headerData', function(){
	header('Access-Control-Allow-Origin: *'); 
	header('Content-type: application/json');	
});


/**
 * Inicializamos el webservice
 */
Flight::start();

?>
