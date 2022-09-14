<?php

include_once(dirname(__FILE__) . "/../models/modelo.class.php");

/**
 * Se invoca clase que puede ser un modelo
 */
$modelo = new Modelo();

/**
 * La clase contiene una funcion obtener nombres, retorna un arreglo de nombres
 */
$params = array(
    "nombres" => $modelo->obtener_nombres()
); 

/**
 * Para pintar la lista de nombre en el cliente es necesario renderizar un archivo php
 * recibira un parametro nombres, con la lista de nombres
 * y se podra acceder mediante 
 * 
 * $_ENV["params"]["{{nombres}}"]
 * 
 * desde la vista php
 */
Response::render("ejemplo_parametro_php.php", $params);
