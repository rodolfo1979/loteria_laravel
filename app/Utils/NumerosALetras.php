<?php
namespace App\Utils;

class NumerosALetras
{

    function __construct()
    {

    }

       // Convertir $numero a letras de monedas
    public static function numerosALetras($importe) {

        $numero = 0;

        if ($importe == "0.00" || $importe == "0" || !$importe ) {
            return "CERO con 00/100 ";
        } else {

            $entero =  explode(".", $importe);

            $array = self::separarTexto($entero[0]);

            $longitud = count($array);

            switch ($longitud) {
                case 1:
                    $numero = self::unidades($array[0]);
                    break;
                case 2:
                    $numero = self::decenas($array[0], $array[1]);
                    break;
                case 3:
                    $numero = self::centenas($array[0], $array[1], $array[2]);
                    break;
                case 4:
                    $numero = self::unidadesDeMillar($array[0], $array[1], $array[2], $array[3]);
                    break;
                case 5:
                    $numero = self::decenasDeMillar($array[0], $array[1], $array[2], $array[3], $array[4]);
                    break;
                case 6:
                    $numero = self::centenasDeMillar($array[0], $array[1], $array[2], $array[3], $array[4], $array[5]);
                    break;
            }

            $entero[1] = !isset($entero[1]) ? '00' : $entero[1];

            return $numero . " ". strtoupper(session("Empresa.cat_moneda_nombre")) . " con ". $entero[1] . "/100";
        }
    }

    public static function unidades($unidad) {
        $unidades = array('UN ','DOS ','TRES ' ,'CUATRO ','CINCO ','SEIS ','SIETE ','OCHO ','NUEVE ');
        return $unidades[$unidad - 1];
    }

    public static function decenas($decena, $unidad) {
        $diez = array('ONCE ','DOCE ','TRECE ','CATORCE ','QUINCE' ,'DIECISEIS ','DIECISIETE ','DIECIOCHO ','DIECINUEVE ');
        $decenas = array('DIEZ ','VEINTE ','TREINTA ','CUARENTA ','CINCUENTA ','SESENTA ','SETENTA ','OCHENTA ','NOVENTA ');

        if ($decena == 0 && $unidad == 0) {
            return "";
        }

        if ($decena == 0 && $unidad > 0) {
            return self::unidades($unidad);
        }

        if ($decena == 1) {
            if ($unidad == 0) {
                return $decenas[$decena -1];
            } else {
                return $diez[$unidad -1];
            }
        } else if ($decena == 2) {
            if ($unidad == 0) {
                return $decenas[$decena -1];
            }
            else if ($unidad == 1) {
                return "VEINTI UNO";
            }
            else {
                return $veinte = "VEINTI " . self::unidades($unidad);
            }
        } else {

            if ($unidad == 0) {
                return $decenas[$decena -1] . " ";
            }
            if ($unidad == 1) {
                return $decenas[$decena -1] . " Y UNO";
            }

            return $decenas[$decena -1] . " Y " . self::unidades($unidad);
        }
    }

    public static function centenas($centena, $decena, $unidad) {
        $centenas = array( "CIENTO ", "DOSCIENTOS ", "TRESCIENTOS ", "CUATROCIENTOS ","QUINIENTOS ","SEISCIENTOS ","SETECIENTOS ", "OCHOCIENTOS ","NOVECIENTOS ");

        if ($centena == 0 && $decena == 0 && $unidad == 0) {
            return "";
        }
        if ($centena == 1 && $decena == 0 && $unidad == 0) {
            return "CIEN ";
        }

        if ($centena == 0 && $decena == 0 && $unidad > 0) {
            return self::unidades($unidad);
        }

        if ($decena == 0 && $unidad == 0) {
            return $centenas[$centena - 1] . "" ;
        }

        if ($decena == 0) {
            $numero = $centenas[$centena - 1] . "" . self::decenas($decena, $unidad);
            return $numero.str_replace(" Y ", " ", true);
        }
        if ($centena == 0) {

            return self::decenas($decena, $unidad);
        }

        return $centenas[$centena - 1] . "" . self::decenas($decena, $unidad);

    }

    public static function unidadesDeMillar($unidadDeMillar, $centena, $decena, $unidad) {
        $numero = self::unidades($unidadDeMillar) . " MIL " . self::centenas($centena, $decena, $unidad);
        $numero = $numero.str_replace("UN MIL ", "MIL ", true);
        if ($unidad == 0) {
            return $numero.str_replace(" Y ", " ", true);
        } else {
            return $numero;
        }
    }

    public static function decenasDeMillar($decenaDeMillar, $unidadDeMillar, $centena, $decena, $unidad) {
        $numero = self::decenas($decenaDeMillar, $unidadDeMillar) . " MIL " . self::centenas($centena, $decena, $unidad);
        return $numero;
    }

    public static function centenasDeMillar($centenaDeMillar, $decenaDeMillar, $unidadDeMillar, $centena, $decena, $unidad) {
        $numero = 0;
        $numero = self::centenas($centenaDeMillar,$decenaDeMillar, $unidadDeMillar) . " MIL " . self::centenas($centena, $decena, $unidad);
        return $numero;
    }

    public static function separarTexto($texto){
        return str_split($texto);
    }


}
