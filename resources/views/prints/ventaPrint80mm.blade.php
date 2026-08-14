@php
    use App\Utils\Fechas;
@endphp
    <!DOCTYPE html>
<html lang="es">
<head>
    <title>{{$fileName}}</title>
    <link rel="stylesheet" href="{{asset('css/viewPrintTicket.css')}}?v={{time()}}">
<body>
<!--mpdf
<htmlpageheader name="myheader">
<table>
    <tr>
        <td width="100%" class="text_center font13">{{$agencia["nombre_comercial"]}}</td>
    </tr>
</table>
</htmlpageheader>

<sethtmlpageheader name="myheader" value="on" show-this-page="1" />

mpdf-->
<table class="">
    <tbody>
    <tr>
        <td width="50%"># Ticket: {{$master["venta_numero"]}}</td>
        <td width="50%">Fecha:{{Fechas::convertToFechaESP($master["fecha_sorteo"])}}</td>
    </tr>
    <tr>
        <td colspan="2">Juego: {{$master["loteria"]}} / {{$master["juego"]}}</td>
    </tr>
    <tr>
        <td colspan="2">Cliente: {{$master["cliente"]}}</td>
    </tr>
    <tr>
        <td colspan="2">Agente: {{$master["vendedor"]}}</td>
    </tr>
    </tbody>
</table>

<table class="border_top">
    <thead>
    <tr>
        <td width="25%" class="text_left">Sorteo</td>
        <td width="30%" class="text_left">Mod</td>
        <td width="20%" class="text_center">Num</td>
        <td width="25%" class="text_right">Inversión</td>
    </tr>
    </thead>
    <tbody>
    @if(count($details))
        @foreach ($details as $det)
            <tr>
                <td class="text_left">{{$det["horaFmt"]}}</td>
                <td class="text_left">{{ substr($det["modalidad"], 0, 10)}}</td>
                <td class="text_center">{{$det["numero"]}}</td>
                <td class="text_right">{{number_format($det["monto"], 2)}}</td>
        @endforeach
    @else
        <tr>
            <td colspan="4" class="text_center">No se encontraron registros</td>
        </tr>
    @endif
    </tbody>
    <troot>
        <tr>
            <td colspan="3" class="text_center border_top font13">Total: ₡</td>
            <td class="text_right border_top font13">
            {{number_format($master["total"], 2)}}</td>
        </tr>
    </troot>
</table>

<p class="font10">*Conserve su Ticket para reclamar el Premio</p>
<p>Una nueva oportunidad para Ganar!!!</p>
<br>
<p class="text_right">{{Fechas::hoyFechaHoraESP()}}</p>
</body>
</html>
