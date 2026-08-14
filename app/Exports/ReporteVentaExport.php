<?php

namespace App\Exports;

use App\Utils\Fechas;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use function App\Http\Exports\mb_strtoupper;

class ReporteVentaExport
{

    private $master;
    private $details;
    private $agencia;
    private $total;

    public function __construct($master, $details, $total, $agencia)
    {
        $this->master = $master;
        $this->details = $details;
        $this->total = $total;
        $this->agencia = $agencia;
        $this->Body();
    }

    private function Body(): void
    {
        $fechaInicio = Fechas::convertToFechaESP($this->fecha_inicio);
        $fechaFin = Fechas::convertToFechaESP($this->fecha_fin);

        $fileName = "VENTAS_AGENCIA" . mb_strtoupper($this->agencia["nombre_comercial"]) . "DEL_" . $fechaInicio . "_AL_" . $fechaFin . ".xlsx";

        $spreadsheet = new Spreadsheet();
        $border = new Border();
        $alignment = new Alignment();
        $bg = new Fill();
        $activeWorksheet = $spreadsheet->getActiveSheet()->setTitle("Hoja1");

        // ENCABEZADOS
        $activeWorksheet->setCellValue('A1', mb_strtoupper($this->agencia["nombre_comercial"]));
        $activeWorksheet->getStyle('A1')->getFont()->setBold(true);
        $activeWorksheet->setCellValue('A2', mb_strtoupper("REPORTE DE VENTAS"));
        $activeWorksheet->getStyle('A2')->getFont()->setBold(true);
        $activeWorksheet->setCellValue('A3', "PERÍODO DEL: " . Fechas::convertToFechaESP($th));
        $activeWorksheet->getStyle('A3')->getFont()->setBold(true);


        // CABECERAS TABLA
        $fila = 5;

        $activeWorksheet->setCellValue("A$fila", "Área");
        $activeWorksheet->getStyle("A$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("A$fila")->getFont()->setBold(true);

        $activeWorksheet->setCellValue("B$fila", "Subsidio");
        $activeWorksheet->getStyle("B$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("B$fila")->getFont()->setBold(true);

        $activeWorksheet->setCellValue("C$fila", "Salario Quincenal");
        $activeWorksheet->getStyle("C$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("C$fila")->getFont()->setBold(true);

        $activeWorksheet->setCellValue("D$fila", "Horas Extras");
        $activeWorksheet->getStyle("D$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("D$fila")->getFont()->setBold(true);

        $activeWorksheet->setCellValue("E$fila", "Horas Feriadas");
        $activeWorksheet->getStyle("E$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("E$fila")->getFont()->setBold(true);

        $activeWorksheet->setCellValue("F$fila", "Vacaciones Descansadas");
        $activeWorksheet->getStyle("F$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("F$fila")->getFont()->setBold(true);

        $activeWorksheet->setCellValue("G$fila", "Vacaciones Pagadas");
        $activeWorksheet->getStyle("G$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("G$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("G$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

        $activeWorksheet->setCellValue("H$fila", "Comisiones S/ Ventas");
        $activeWorksheet->getStyle("H$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("H$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("H$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

        $activeWorksheet->setCellValue("I$fila", "Bonos Producción");
        $activeWorksheet->getStyle("I$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("I$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("I$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

        $activeWorksheet->setCellValue("J$fila", "Incentivos");
        $activeWorksheet->getStyle("J$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("J$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("J$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

        $activeWorksheet->setCellValue("K$fila", "TOTAL DEVENGADO");
        $activeWorksheet->getStyle("K$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("K$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("K$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
        $activeWorksheet->getStyle("k$fila")->getFill()->setFillType($bg::FILL_SOLID)->getStartColor()->setRGB("Cyan");

        $activeWorksheet->setCellValue("L$fila", "INSS Laboral");
        $activeWorksheet->getStyle("L$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("L$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("L$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

        $activeWorksheet->setCellValue("M$fila", "I.R.");
        $activeWorksheet->getStyle("M$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("M$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("M$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

        $activeWorksheet->setCellValue("N$fila", "Llegadas tardes");
        $activeWorksheet->getStyle("N$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("N$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("N$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

        $activeWorksheet->setCellValue("O$fila", "Embargos");
        $activeWorksheet->getStyle("O$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("O$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("O$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

        $activeWorksheet->setCellValue("P$fila", "Anticipos Sueldos");
        $activeWorksheet->getStyle("P$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("P$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("P$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

        $activeWorksheet->setCellValue("Q$fila", "Otras Deducciones");
        $activeWorksheet->getStyle("Q$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("Q$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("Q$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

        $activeWorksheet->setCellValue("R$fila", "TOTAL DEDUCCIONES");
        $activeWorksheet->getStyle("R$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("R$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("R$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
        $activeWorksheet->getStyle("R$fila")->getFill()->setFillType($bg::FILL_SOLID)->getStartColor()->setRGB("Red");


        $activeWorksheet->setCellValue("S$fila", "TOTAL A PAGAR");
        $activeWorksheet->getStyle("S$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("S$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("S$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
        $activeWorksheet->getStyle("S$fila")->getFill()->setFillType($bg::FILL_SOLID)->getStartColor()->setRGB("Green");

        $activeWorksheet->setCellValue("T$fila", "INSS Patronal");
        $activeWorksheet->getStyle("T$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("T$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("T$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

        $activeWorksheet->setCellValue("U$fila", "INATEC");
        $activeWorksheet->getStyle("U$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("U$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("U$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

        $activeWorksheet->setCellValue("V$fila", "Indemnización");
        $activeWorksheet->getStyle("V$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("V$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("V$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

        $activeWorksheet->setCellValue("W$fila", "Vacaciones");
        $activeWorksheet->getStyle("W$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("W$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("W$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

        $activeWorksheet->setCellValue("X$fila", "Aguinaldo");
        $activeWorksheet->getStyle("X$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
        $activeWorksheet->getStyle("X$fila")->getFont()->setBold(true);
        $activeWorksheet->getStyle("X$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

        // DETALLES TABLAS
        $fila += 1;

        if (count($this->details)) {
            // DETALLES
            foreach ($this->details as $de) {

                $activeWorksheet->setCellValue("A$fila", $de["area_agencia_nombre"]);
                $activeWorksheet->getStyle("A$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);

                $activeWorksheet->setCellValue("B$fila", number_format($de["subsidio"], 2));
                $activeWorksheet->getStyle("B$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("B$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("C$fila", number_format($de["salario_base"], 2));
                $activeWorksheet->getStyle("C$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("C$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("D$fila", number_format($de["horas_extras"], 2));
                $activeWorksheet->getStyle("D$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("D$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("E$fila", number_format($de["horas_feriadas"], 2));
                $activeWorksheet->getStyle("E$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("E$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("F$fila", number_format($de["vacaciones_descansadas"], 2));
                $activeWorksheet->getStyle("F$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("F$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("G$fila", number_format($de["vacaciones_pagadas"], 2));
                $activeWorksheet->getStyle("G$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("G$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("H$fila", number_format($de["comisiones_ventas"], 2));
                $activeWorksheet->getStyle("H$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("H$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("I$fila", number_format($de["bonos_produccion"], 2));
                $activeWorksheet->getStyle("I$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("I$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("J$fila", number_format($de["incentivos"], 2));
                $activeWorksheet->getStyle("J$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("J$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("K$fila", number_format($de["total_devengado"], 2));
                $activeWorksheet->getStyle("K$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("K$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
                $activeWorksheet->getStyle("k$fila")->getFill()->setFillType($bg::FILL_SOLID)->getStartColor()->setRGB("Cyan");

                $activeWorksheet->setCellValue("L$fila", number_format($de["inss_laboral_calc"], 2));
                $activeWorksheet->getStyle("L$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("L$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("M$fila", number_format($de["ir_calc"], 2));
                $activeWorksheet->getStyle("M$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("M$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("N$fila", number_format($de["llegadas_tardes"], 2));
                $activeWorksheet->getStyle("N$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("N$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("O$fila", number_format($de["embargos"], 2));
                $activeWorksheet->getStyle("O$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("O$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("P$fila", number_format($de["anticipos_sueldos"], 2));
                $activeWorksheet->getStyle("P$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("P$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("Q$fila", number_format($de["otras_deducciones"], 2));
                $activeWorksheet->getStyle("Q$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("Q$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("R$fila", number_format($de["total_deducciones"], 2));
                $activeWorksheet->getStyle("R$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("R$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
                $activeWorksheet->getStyle("R$fila")->getFill()->setFillType($bg::FILL_SOLID)->getStartColor()->setRGB("Red");

                $activeWorksheet->setCellValue("S$fila", number_format($de["total_pagar"], 2));
                $activeWorksheet->getStyle("S$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("S$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
                $activeWorksheet->getStyle("S$fila")->getFill()->setFillType($bg::FILL_SOLID)->getStartColor()->setRGB("Green");

                $activeWorksheet->setCellValue("T$fila", number_format($de["inss_patronal_calc"], 2));
                $activeWorksheet->getStyle("T$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("T$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("U$fila", number_format($de["inatec_calc"], 2));
                $activeWorksheet->getStyle("U$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("U$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("V$fila", number_format($de["indemnizacion"], 2));
                $activeWorksheet->getStyle("V$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("V$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("W$fila", number_format($de["vacaciones"], 2));
                $activeWorksheet->getStyle("W$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("W$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $activeWorksheet->setCellValue("X$fila", number_format($de["aguinaldo"], 2));
                $activeWorksheet->getStyle("X$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
                $activeWorksheet->getStyle("X$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);

                $fila += 1;
            }

            // AHORA IMPRIMIR LOS TOTALES
            $activeWorksheet->setCellValue("A$fila", "TOTALES (C$)");
            $activeWorksheet->getStyle("A$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("A$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("B$fila", number_format($this->total["subsidio"], 2));
            $activeWorksheet->getStyle("B$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("B$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("B$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("C$fila", number_format($this->total["salario_base"], 2));
            $activeWorksheet->getStyle("C$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("C$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("C$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("D$fila", number_format($this->total["horas_extras"], 2));
            $activeWorksheet->getStyle("D$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("D$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("D$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("E$fila", number_format($this->total["horas_feriadas"], 2));
            $activeWorksheet->getStyle("E$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("E$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("E$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("F$fila", number_format($this->total["vacaciones_descansadas"], 2));
            $activeWorksheet->getStyle("F$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("F$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("F$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("G$fila", number_format($this->total["vacaciones_pagadas"], 2));
            $activeWorksheet->getStyle("G$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("G$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("G$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("H$fila", number_format($this->total["comisiones_ventas"], 2));
            $activeWorksheet->getStyle("H$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("H$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("H$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("I$fila", number_format($this->total["bonos_produccion"], 2));
            $activeWorksheet->getStyle("I$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("I$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("I$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("J$fila", number_format($this->total["incentivos"], 2));
            $activeWorksheet->getStyle("J$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("J$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("J$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("K$fila", number_format($this->total["total_devengado"], 2));
            $activeWorksheet->getStyle("K$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("K$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("K$fila")->getFont()->setBold(true);
            $activeWorksheet->getStyle("k$fila")->getFill()->setFillType($bg::FILL_SOLID)->getStartColor()->setRGB("Cyan");

            $activeWorksheet->setCellValue("L$fila", number_format($this->total["inss_laboral_calc"], 2));
            $activeWorksheet->getStyle("L$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("L$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("L$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("M$fila", number_format($this->total["ir_calc"], 2));
            $activeWorksheet->getStyle("M$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("M$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("M$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("N$fila", number_format($this->total["llegadas_tardes"], 2));
            $activeWorksheet->getStyle("N$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("N$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("N$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("O$fila", number_format($this->total["embargos"], 2));
            $activeWorksheet->getStyle("O$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("O$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("O$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("P$fila", number_format($this->total["anticipos_sueldos"], 2));
            $activeWorksheet->getStyle("P$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("P$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("P$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("Q$fila", number_format($this->total["otras_deducciones"], 2));
            $activeWorksheet->getStyle("Q$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("Q$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("Q$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("R$fila", number_format($this->total["total_deducciones"], 2));
            $activeWorksheet->getStyle("R$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("R$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("R$fila")->getFont()->setBold(true);
            $activeWorksheet->getStyle("R$fila")->getFill()->setFillType($bg::FILL_SOLID)->getStartColor()->setRGB("Red");

            $activeWorksheet->setCellValue("S$fila", number_format($this->total["total_pagar"], 2));
            $activeWorksheet->getStyle("S$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("S$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("S$fila")->getFont()->setBold(true);
            $activeWorksheet->getStyle("S$fila")->getFill()->setFillType($bg::FILL_SOLID)->getStartColor()->setRGB("Green");

            $activeWorksheet->setCellValue("T$fila", number_format($this->total["inss_patronal_calc"], 2));
            $activeWorksheet->getStyle("T$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("T$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("T$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("U$fila", number_format($this->total["inatec_calc"], 2));
            $activeWorksheet->getStyle("U$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("U$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("U$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("V$fila", number_format($this->total["indemnizacion"], 2));
            $activeWorksheet->getStyle("V$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("V$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("V$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("W$fila", number_format($this->total["vacaciones"], 2));
            $activeWorksheet->getStyle("W$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("W$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("W$fila")->getFont()->setBold(true);

            $activeWorksheet->setCellValue("X$fila", number_format($this->total["aguinaldo"], 2));
            $activeWorksheet->getStyle("X$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("X$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_RIGHT);
            $activeWorksheet->getStyle("X$fila")->getFont()->setBold(true);

        } else {
            $activeWorksheet->setCellValue("A$fila", "NO SE ENCONTRARON DATOS DE LA PLANILLA.");
            $activeWorksheet->mergeCells("A$fila:X$fila");
            $activeWorksheet->getStyle("A$fila:X$fila")->getBorders()->getAllBorders()->setBorderStyle($border::BORDER_THIN);
            $activeWorksheet->getStyle("A$fila:X$fila")->getFont()->setBold(true);
            $activeWorksheet->getStyle("A$fila:X$fila")->getAlignment()->setHorizontal($alignment::HORIZONTAL_CENTER);
        }

        // HACER LAS COLUMNAS QUE TOMEN EL TAMANYO COMPLETO
        $activeWorksheet->getColumnDimension("A")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("B")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("C")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("D")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("E")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("F")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("G")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("H")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("I")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("J")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("K")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("L")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("M")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("N")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("O")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("P")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("Q")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("R")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("S")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("T")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("U")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("V")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("W")->setAutoSize(true);
        $activeWorksheet->getColumnDimension("X")->setAutoSize(true);

        // NECESARIO PARA LA SALIDA
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment; filename=$fileName");
        header("Cache-Control: max-age=0");

        // FIN DE LA SALIDA
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save('php://output');
    }
}
