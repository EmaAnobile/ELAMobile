<?php
	class Conexion {

		private $dbTarget = "prueba_bd";
		private $host 	  = "localhost";
		private $usuario  = "root";
		private $password = "";
		private $link;
		
		/* abre la conexión, y devuelve el resultado al pundo de llamada  */
		public function conectar() {
			$this->link = mysqli_connect($this->host, $this->usuario, $this->password, $this->dbTarget);
			return $this->link;
		}
		
		/* devuelve el error al conectar*/
		public function error_al_conectar() {
			return mysqli_connect_errno();
		}
		
		/* ejecuta una determinada query y devuelve el resultado al pundo de llamada */
		public function query($query) {
			if ($resultado = mysqli_query($this->link, $query)) {
				return new Resultado($resultado);
			} else {
				return false;
			}
		}
		
		public function error_query() {
			return mysqli_error($this->link);
		}
		
				/* ejecuta una determinada query y devuelve el resultado al pundo de llamada */
		public function multi_query($query) {
			if (mysqli_multi_query($this->link, $query)) {
				return new Multiples_Resultado($this->link);
			} else {
				return false;
			}
		}
		
		//obtiene el ultimo ID generado automáticamente
		public function ultimoId() {
			return mysqli_insert_id($this->link);
		}
		
		/* cerrar la conexión */
		public function desconectar() {	
			mysqli_close($this->link);
		}
	}
	
	//clase que maneja los registros devueltos por la consulta
	class Resultado {
		
		private $resultado;
		
		//constructor
		public function __construct($res) {
			$this->resultado = $res;
		}
		
		/* devuelve una fila del conjunto resultante de la consulta */
		public function get_row() {
			return $this->resultado->fetch_assoc();
		}
		
		//devuelve la cantidad de registros obtenidos en la consulta
		public function cantidad_registros() {
			return mysqli_num_rows($this->resultado);
		}
		
	}
	
	class Multiples_Resultado {
		
		private $resultado = array();
		
		//constructor
		public function __construct($link) {
			do {
				if ($result = mysqli_store_result($link)) {
					array_push($this->resultado, $result);
				}
				
				if (!mysqli_more_results($link)) {
					break;
				}
			} while (mysqli_next_result($link));
		}
		
		/* devuelve una fila del conjunto resultante de la consulta */
		public function get_row($resultado) {
			return $this->resultado[$resultado]->fetch_assoc();
		}
		
		//devuelve la cantidad de registros obtenidos en la consulta
		public function cantidad_registros($resultado) {
			return mysqli_num_rows($this->resultado[$resultado]);
		}
		
		//devuelve la cantidad de resultados
		public function cantidad_resultados() {
			return count($this->resultado);
		}
		
	}
?>