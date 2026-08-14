<?php

namespace App\Helpers;

use Carbon\Carbon;

define('MY_SECRET_KEY', '#hdev.');

class MyHash
{

    function createHash($data)
    {
        // Convertir el array a JSON
        $jsonData = json_encode($data);

        // Crear el hash HMAC con la clave secreta
        $hash = hash_hmac('sha256', $jsonData, MY_SECRET_KEY);

        // Devolver el JSON concatenado con el hash
        return base64_encode($jsonData . '|' . $hash);
    }

    // Función para validar el hash y obtener los datos
    function validateHash($hashedData)
    {
        // Decodificar la cadena base64
        $decodedData = base64_decode($hashedData);

        // Separar los datos del hash
        list($jsonData, $hash) = explode('|', $decodedData);

        // Recalcular el hash
        $calculatedHash = hash_hmac('sha256', $jsonData, MY_SECRET_KEY);

        // Verificar que los hashes coinciden
        if (hash_equals($calculatedHash, $hash)) {
            // Devolver los datos decodificados
            return json_decode($jsonData, true);
        } else {
            // Hash no válido
            return false;
        }
    }

}
