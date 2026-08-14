<?php

namespace App\Prints;

use App\Utils\Fechas;
use App\Utils\FPDFMultiCell;

class ReporteVentaPrint extends FPDFMultiCell
{
    private $width = 195;
    private $widthDet = [20, 30, 55, 30, 30, 30];
    private $data;
    private $params;
    private $cliente;
    private $moneda;

    public function __construct($params, $data, $cliente, $orientation = "P", $size = "Letter")
    {
        parent::__construct($orientation, "mm", $size);

        $this->params = $params;
        $this->data = $data;
        $this->cliente = $cliente;
        $this->moneda = \Session::get('moneda_default') ?? "C$";

        $fileName = "ESTADO-CUENTA-CLIENTE-" . $this->cliente["nombres"] . "-DEL-" . Fechas::convertToFechaESP($this->params["fecha_inicio"]) . "-AL-" . Fechas::convertToFechaESP($this->params["fecha_fin"]) . ".xlsx";

        $this->AddPage();
        $this->AliasNbPages();
        $this->SetAutoPageBreak(true, 11);
        $this->SetMargins(10, 10);
        $this->SetFont('Arial', '', 9);
        $this->SetTitle($fileName);
        $this->Body();
        $this->Output("I", $fileName);
        exit;
    }

    public function Header(): void
    {
        $this->SetDrawColor(80, 80, 80);
        $this->SetLineWidth(.1);

        $this->SetFillColor(255, 255, 255);

        $this->SetFont("Arial", "B", 10);
        $this->Cell($this->width, 4, utf8_decode($this->params["tienda_nombre_comercial"]), 0, 0, "C");
        $this->Ln();
        $this->SetFont("Arial", "B", 9);
        $this->Cell($this->width / 2, 4, utf8_decode("ESTADO DE CUENTA DE CLIENTE"), 0, 0, "L");
        $this->Cell($this->width / 2, 4, utf8_decode("PERÍODO DEL ") . Fechas::convertToFechaESP($this->params["fecha_inicio"]) . " AL " . Fechas::convertToFechaESP($this->params["fecha_fin"]), 0, 0, "R");
        $this->Ln();
        $this->Cell($this->width, 4, utf8_decode($this->params["tipo_estado_cuenta"]), 0, 0, "L");
        $this->Ln();
        $this->Cell($this->width, 4, utf8_decode("CLIENTE: " . $this->cliente["nombres"]), 0, 0, "L");
        $this->Ln();
        $this->Cell($this->width, 4, utf8_decode("RUC/CÉDULA: " . $this->cliente["numero_identidad"]), 0, 0, "L");
        $this->Ln(6);

        $this->SetFillColor(246, 246, 246);
        $this->SetFont("Arial", "B", 9);
        $this->Cell($this->widthDet[0], 4, utf8_decode("FECHA"), 1, 0, "L", 1);
        $this->Cell($this->widthDet[1], 4, utf8_decode("REFERENCIA"), 1, 0, "L", 1);
        $this->Cell($this->widthDet[2], 4, utf8_decode("DESCRIPCIÓN"), 1, 0, "L", 1);
        $this->Cell($this->widthDet[3], 4, utf8_decode("CARGOS $this->moneda"), 1, 0, "R", 1);
        $this->Cell($this->widthDet[4], 4, utf8_decode("ABONOS $this->moneda"), 1, 0, "R", 1);
        $this->Cell($this->widthDet[5], 4, utf8_decode("SALDO $this->moneda"), 1, 0, "R", 1);
        $this->Ln();
    }

    public function Body(): void
    {
        $this->SetDrawColor(80, 80, 80);
        $this->SetLineWidth(.1);

        $this->SetY(36);

        if (count($this->data["resumen"])) {

            // PARA LOS MULTICELL
            $this->SetWidths($this->widthDet);
            $this->SetLineHeight(4);
            $this->SetAligns(array("L", "L", "L", "R", "R", "R"));
            // PARA LOS MULTICELL

            foreach ($this->data["resumen"] as $de) {

                // SET FIL COLOR
                if ($de["saldo"] > 0) {
                    $this->SetFillColor(255, 255, 204);
                } else {
                    $this->SetFillColor(229, 255, 204);
                }
                $this->SetFont("Arial", "", 9);
                $this->Cell($this->widthDet[0], 4, Fechas::convertToFechaESP($de["fecha_factura"]), 1, 0, "L", 1);
                $this->Cell($this->widthDet[1], 4, $de["numero"], 1, 0, "L", 1);
                $this->Cell($this->widthDet[2], 4, utf8_decode($de["descripcion"]), 1, 0, "L", 1);
                $this->Cell($this->widthDet[3], 4, number_format($de["cargo"], 2), 1, 0, "R", 1);
                $this->Cell($this->widthDet[4], 4, number_format($de["abono"], 2), 1, 0, "R", 1);
                $this->Cell($this->widthDet[5], 4, number_format($de["saldo"], 2), 1, 0, "R", 1);
                $this->Ln();

                // AHORA VER SI HAY ABONOS
                $this->SetFont("Arial", "", 8.8);
                $this->SetFillColor(255, 255, 255);
                foreach ($de["detalles_abonos"] as $abo) {
                    // MULTICELL
                    $this->Row([
                        Fechas::convertToFechaESP($abo["fecha_recibo"]),
                        $abo["numero"],
                        utf8_decode($abo["descripcion"]),
                        number_format($abo["cargo"], 2),
                        number_format($abo["abono"], 2),
                        number_format($abo["saldo"], 2),
                    ]);
                }
            }

            $this->SetFillColor(246, 246, 246);
            $this->SetFont("Arial", "B", 9);
            $this->Cell($this->widthDet[0] + $this->widthDet[1] + $this->widthDet[2], 4, utf8_decode("TOTALES $this->moneda:"), 1, 0, "R", 1);
            $this->Cell($this->widthDet[3], 4, number_format($this->data["totales"]["total_cargos"], 2), 1, 0, "R", 1);
            $this->Cell($this->widthDet[4], 4, number_format($this->data["totales"]["total_abonos"], 2), 1, 0, "R", 1);
            $this->Cell($this->widthDet[5], 4, number_format($this->data["totales"]["total_saldos"], 2), 1, 0, "R", 1);
            $this->Ln();

        } else {
            $this->SetFont("Arial", "B", 9);
            $this->Cell($this->width, 5, "NO SE ENCONTRARON CUENTAS", 1, 0, "C");
            $this->Ln();
        }
    }

    public function Footer(): void
    {
        $this->SetY(-11.5);
        $this->SetFont("Arial", "", 7);
        $this->Cell($this->width / 2, 4, utf8_decode("Página " . $this->pageNo() . "/{nb}"), 0, 0, "L");
        $this->Cell($this->width / 2, 4, Fechas::hoyFechaESP(), 0, 0, "R");
        $this->Ln();
    }

}
