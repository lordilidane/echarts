

<?php
//felipe 1
//$mysqli = new mysqli("192.168.0.104", "root","", "test");
$mysqli = new mysqli("localhost", "usuario","contraseña", "base-de-datos");
//$mysqli = new mysqli("192.168.1.5:3306", "root", "", "ejemplojson");

/* verificar la conexión */
if ($mysqli->connect_errno) {
	//$mysqli = new mysqli("localhost", "root","", "test");
	if ($mysqli->connect_errno) {
        printf("Conexión fallida: %s\n", $mysqli->connect_error);
    exit();
  }
}

$consulta = "select usoFrecuencia.frecuenciaCentral, usoFrecuencia.fechaHora,CONCAT ( HOUR(usoFrecuencia.fechaHora),':',MINUTE(usoFrecuencia.fechaHora),':',SECOND(usoFrecuencia.fechaHora)) as hora_minuto_segudo , usoFrecuencia.enUso
from usoFrecuencia
inner join 
    (select usoFrecuencia.frecuenciaCentral, max(usoFrecuencia.fechaHora) as ts
    from usoFrecuencia
    group by frecuenciaCentral) maxt
on (usoFrecuencia.frecuenciaCentral = maxt.frecuenciaCentral and usoFrecuencia.fechaHora = maxt.ts) GROUP by (usoFrecuencia.frecuenciaCentral) ORDER BY usoFrecuencia.frecuenciaCentral ASc";


if ($resultado = $mysqli->query($consulta)) {
    /* obtener un array asociativo */
    while ($fila = $resultado->fetch_assoc()) {
    		$datos[] = $fila;
    }
    /* liberar el conjunto de resultados */
    $resultado->free();
}else{
	echo "hubo un error con la consulta!!!";
	exit();
}

/* cerrar la conexión */
$mysqli->close();

echo json_encode($datos);

?>