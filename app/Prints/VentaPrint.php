<?php

namespace App\Prints;

use Mpdf\Mpdf;

class VentaPrint extends Mpdf
{
    private $agencia;
    private $master;
    private $details;
    public $barcode = null;
    private $fileName;
    private $width = 72;
    private $heightBase = 70;
    private $heightXRow = 5;

    public function __construct($agencia, $master, $details, $orientation = "P", $format = [])
    {
        // CALCULATE HEIGHT DYNAMIC
        if (empty($format)) {
            $format = [$this->width, (count($details) * $this->heightXRow) + $this->heightBase];
        }

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontData = $defaultFontConfig['fontdata'];

        // SET CONFIG FOR CONSTRUCT
        $config = [
            "mode" => "utf-8",
            "format" => $format,
            "orientation" => $orientation,
            "margin_top" => 9,
            "margin_left" => 1,
            "margin_right" => 1,
            "margin_bottom" => 5,
            "margin_header" => 4,
            "margin_footer" => 4,
            'tempDir' => storage_path('app/temp'),
            'fontDir' => array_merge($fontDirs, [
                public_path('fonts/'),
            ]),
            'fontdata' => $fontData + [ // lowercase letters only in font key
                    'ticketing' => [
                        'R' => 'Ticketing.ttf',
                        'I' => 'Ticketing.ttf',
                    ]
                ],
            'default_font' => 'ticketing'
        ];

        parent::__construct($config);

        $this->agencia = $agencia;
        $this->master = $master;
        $this->details = $details;

        $this->fileName = strtoupper($this->agencia["nombre_comercial"]) . "_TICKET_" . strtoupper($this->master["venta_numero"]) . ".pdf";

        $this->SetTitle($this->fileName);
        $this->SetAuthor(strtoupper($this->agencia["nombre_comercial"]));
        $this->SetDisplayMode("fullpage");
        $this->SetBody();
        $this->Output($this->fileName, "I");
    }

    private function SetBody()
    {
        $html = view("prints.ventaPrint80mm", [
            "agencia" => $this->agencia,
            "master" => $this->master,
            "details" => $this->details,
            "fileName" => $this->fileName,
            "barcode" => $this->barcode,
        ])->render();
        $this->WriteHTML($html);
    }
}
