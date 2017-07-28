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
 * Atributos de conexion a la base de datos
 */

define("DBMANAGER", "mysql");
define("URL_WEBSERVICE", "/");
define("HOSTNAME","localhost");
define("DBNAME","restaurant");
define("HOSTUSER","root");
define("HOSTPASS", "admin123");
	

/**
 * Registramos atributo db como propiedad de Flight
 */
Flight::register('db', 'PDO', array( 
								DBMANAGER':host='.HOSTNAME.';dbname='.DBNAME ,
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
 * @example  ~/facturacion/factura_id/265
 * 
 * @param {number} @table : Nombre de la tabla
 * @param {@field_value} Coincidencia buscada
 */
Flight::route("GET /webservice/@table(/@field_name(/@field_id))", 
	function($table,$param1,$param2) {
	$db = Flight::db();


	$query = '';
	if (isset($field_name) && isset($field_value)){

		$query = "SELECT * 
    			  FROM $table 
    			  "
		// Retorna datos por un campo en especifico
		else if (isset($param2)){				
			$keyName = Flight::setFieldKeyName($param1,"",false);
			$query = "WHERE $keyName = $param2"; 
		}	
	}
	else {

	}

	$query .= Flight::limitRow();


    $stmt = $db->prepare(.$query);
    
    $stmt->execute();

    header('Content-type: application/json');
	echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS));

	

	$db = null;	 
});


/**
 * Inserta en las rutas ~/tabla/field_id 
 * 
 * @param {number} @table : Nombre de la tabla
 * @param {@field_name} Nombre del campo 
 * @param {@field_value} Coincidencia buscada
 */
// Flight::route("POST /webservice/@table(/@field_name(/@field_value))", 
// 	function($table,$param1,$param2) {
	
// 	$db = Flight::db();

// 	if (Flight::request()->method == "GET"){
// 		$query = '';	

// 		if (isset($param1)){
// 			// Retorna datos por id
// 			if (is_numeric($param1) && !isset($param2)){
// 				$keyName = Flight::setFieldKeyName($table,"id_",false);
// 				$query = "WHERE $keyName = $param1";
// 			}
// 			// Retorna datos por un campo en especifico
// 			else if (isset($param2)){				
// 				$keyName = Flight::setFieldKeyName($param1,"",false);
// 				$query = "WHERE $keyName = $param2"; 
// 			}	
// 		}

// 		$query .= Flight::limitRow($_GET['init'], $_GET[]);

	
// 	    $stmt = $db->prepare("SELECT * 
// 	    					  FROM $table ".$query);
	    
// 	    $stmt->execute();

// 	    header('Content-type: application/json');
// 		echo json_encode($stmt->fetchAll(PDO::FETCH_CLASS));

// 	}
	

// 	$db = null;	 
// });


// Verifica si las variables init, y limit estan inicializas, en dicho caso, 
// retorna el query necesario 
Flight::map('limitRow', function($init, $limit){
	$query = '';

	if (NULL === $init)	$init = 0;
	if (NULL === $limit) $limit = 25;

	try {
		
		if (!is_numeric($init) || !is_numeric($limit))
			throw new Exception("Valores del init o limit son incorrectos", 1);
			  
		$query = " LIMIT $init, $limit";

	} catch (Exception $e) {
		echo $e->getMessage();
	}
});


// Genera los queries del buscador 
Flight::map('setValueFilterSearch', function($getParam,$field,
											 $boolean = "="){
	$queries = "";

	if (isset($getParam)){
		$queries = " $field $boolean $getParam";
		return $queries;
	}
	return "";

});


/**
 * Verificamos la procedencia de los accesos
 */
Flight::map('controlOrigin', function($permiso = '*'){
	header('Access-Control-Allow-Origin: $permiso'); 	
});

Flight::controlOrigin();
Flight::start();

?>
