<?php

/**
 * Se invoca clase que puede ser un modelo
 */
$modelo = new Modelo();

/**
 * La clase contiene una funcion sumar que recibe los parametros enviados en el url
 */
$suma = $modelo->sumar($parameters[0], $parameters[1]);

/**
 * Se renderiza enviando parámetro en un arreglo con la clausula [llave => valor] 
 * el nombre de esta llave debe estar en el html encerradas en {{llave}}
 * ejemplo: {{suma}}
 * 
 * Esto se puede poner con la misma regla en los archivos php, aunque también
 * se puede llamar desde la variable de entorno
 * que podras usar en la vista html de la siguiente manera 
 * 
 * $_ENV["params"]["{{suma}}"]
 */
Response::render("ejemplo_parametro.html", ["suma" => $suma]);
