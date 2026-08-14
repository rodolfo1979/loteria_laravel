<?php

namespace App\Utils;

use Carbon\Carbon;

class Fechas
{
    private static $dias = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
    private static $diasArray = [
        ['id' => 1, 'name' => 'Lunes'],
        ['id' => 2, 'name' => 'Martes'],
        ['id' => 3, 'name' => 'Miércoles'],
        ['id' => 4, 'name' => 'Jueves'],
        ['id' => 5, 'name' => 'Viernes'],
        ['id' => 6, 'name' => 'Sábado'],
        ['id' => 7, 'name' => 'Domingo']
    ];
    private static $diasCortos = ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"];
    private static $meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    private static $mesesCortos = ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sept", "Oct", "Nov", "Dic"];

    public static function diaNombre($dia)
    {
        return self::$dias[$dia];
    }

    public static function diaNombreCorto($dia)
    {
        return self::$diasCortos[$dia];
    }

    public static function mesNombre($mes)
    {
        return self::$meses[$mes - 1];
    }

    public static function mesNombreCorto($mes)
    {
        return self::$mesesCortos[$mes - 1];
    }

    public static function trimestreNombre($mes)
    {
        return self::$mesesStructure[$mes - 1]["trimestre"];
    }

    public static function trimestreId($mes)
    {
        return self::$mesesStructure[$mes - 1]["trimestre_id"];
    }

    public static function getDias()
    {
        return self::$dias;
    }

    public static function getMeses()
    {
        return self::$meses;
    }

    public static function fechaExcelToGMDATE($fecha)
    {
        if ($fecha) {
            $unix_date = ($fecha - 25569) * 86400;

            return gmdate("Y-m-d", $unix_date);
        }

    }

    public static function hoyFechaESP(): string
    {
        return Carbon::now()->format("d/m/Y");
    }

    public static function hoyFechaHoraESP(): string
    {
        return Carbon::now()->format("d/m/Y h:i A");
    }

    public static function convertToFechaESP($fecha): string
    {
        return Carbon::parse($fecha)->format("d/m/Y");
    }

    public static function convertToHoraESP($hora): string
    {
        return Carbon::parse($hora)->format("h:i A");
    }

    public static function convertToFechaHoraESP($hora): string
    {
        return Carbon::parse($hora)->format("d/m/Y h:i A");
    }

    public static function getDiasArray()
    {
        return self::$diasArray;
    }

    // HORAS
    public static function getHorasActivas($inicio = 6, $fin = 24)
    {
        $horaInicio = Carbon::createFromTime($inicio, 0);
        $horaFin = Carbon::createFromTime($fin, 0);

        $horas = [];

        while ($horaInicio->lessThanOrEqualTo($horaFin)) {
            // Formatear la hora en el formato h:i A
            $horas[] = $horaInicio->format('h:i A');
            // Añadir 30 minutos
            $horaInicio->addMinutes(30);
        }

        return $horas;
    }

    public static function getPartesDelDia($horaMin)
    {
        $dateTime = new \DateTime($horaMin);
        $hora = $dateTime->format("H");

        if ($hora >= 6 && $hora < 12) {
            return "Mañana";
        } elseif ($hora >= 12 && $hora < 18) {
            return "Tarde";
        } else {
            return "Noche";
        }
    }


}

