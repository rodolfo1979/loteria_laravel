<?php

namespace App\Utils;

// HERRAMIENTAS QUE NO SE CONECTAN A BASE DE DATOS


class UtilsGenerales
{

    private static $padLength = 8;
    private static $padString = 0;
    private static $padType = STR_PAD_LEFT;

    public static function documentoNumero($numeroAnterior, $prefix = "", $padLength = null, $padString = null, $padType = null): string
    {

        if (!$padLength) {
            $padLength = self::$padLength;
        }

        if (!$padString) {
            $padString = self::$padString;
        }

        if (!$padType) {
            $padType = self::$padType;
        }

        // VALIDAR SI TRAE LETRA EN LA PRIMERA POS
        if ($numeroAnterior == "0") {
            $numeroNuevo = (int)$numeroAnterior + 1;
        } else {
            $numeroNuevo = (int)preg_replace("/[^0-9]/", "", substr($numeroAnterior, 1)) + 1;
        }

        return $prefix . str_pad($numeroNuevo, $padLength, $padString, $padType);

    }

    // LIMPIAR STRING
    public static function cleanString($string)
    {

        $string = trim($string);

        $string = str_replace(
            array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
            array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
            $string
        );

        $string = str_replace(
            array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
            array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
            $string
        );

        $string = str_replace(
            array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
            array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
            $string
        );

        $string = str_replace(
            array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
            array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
            $string
        );

        $string = str_replace(
            array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
            array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
            $string
        );

        $string = str_replace(
            array('ñ', 'Ñ', 'ç', 'Ç'),
            array('n', 'N', 'c', 'C',),
            $string
        );

        //Esta parte se encarga de eliminar cualquier caracter extraño
        $string = str_replace(
            array("\\", "¨", "º", "~",
                "#", "@", "|", "!", "\"",
                "·", "$", "%", "&", "/",
                "(", ")", "?", "'", "¡",
                "¿", "[", "^", "`", "]",
                "+", "}", "{", "¨", "´",
                ">", "< ", ";", ",", ":", " ", "'", "-", "_", "/"),
            '',
            $string
        );
        return $string;
    }

    // CONVERTIR NUMEROS STRING SEPARADOS POR COMA A ARRAY
    public static function convertStringNumbertoArray($string)
    {
        // Step 1: Split the string into individual elements
        $array = explode(",", $string);

        // Step 2: Trim whitespace from each element
        $arrayTrim = array_map('trim', $array);

        // Step 3: Convert each element from string to integer
        $arrayInt = array_map('intval', $arrayTrim);

        // Step 4: Remove duplicate elements
        $arrayUnique = array_unique($arrayInt);

        // Step 5: Sort the array in ascending order
        sort($arrayUnique, SORT_NUMERIC);

        return $arrayUnique;
    }

    // VALIDAR QUE SEA UN CODIGO DE BARRA EAN13
    public static function esCodigoDeBarrasEAN13($codigoBarras)
    {
        // Eliminar espacios en blanco del código de barras
        $codigoBarras = str_replace(' ', '', $codigoBarras);

        // Verificar si es una cadena numérica de 13 dígitos
        if (preg_match('/^[0-9]{13}$/', $codigoBarras)) {
            // También puedes agregar validaciones adicionales según las reglas específicas de EAN-13 si es necesario.
            return true;
        } else {
            return false;
        }
    }

}
