<?php

namespace App\Http\Controllers;

use App\Utils\ticketera;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Milon\Barcode\DNS1D;
use Milon\Barcode\DNS2D;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Milon\Barcode\Facades\Barcode;
use Milon\Barcode\Facades\DNS1DFacade;
use TCPDF;

class print_controller extends Controller
{
    public function impresion_prueba()
    {
        dd("recepcionista");
    }

    public function impresion_gastos(Request $request)
    {

        $param = $request->all();


        try {
            ticketera::imprimir_gasto(
                $param["fecha_impresion"],
                $param["descripcion"],
                $param["tipo_gasto"],
                $param["code"],
                $param["recepcionista"],
                $param["monto"]
            );

            return response()->json([
                'message' => 'la impresion se ha ejecutado exitosamente',
                'error' => '',
                'success' => true,
                'data' =>  '',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'error del servidor',
                'error' => $th->getMessage(),
                'success' => false,
                'data' => '',
            ]);
        }
    }


    public function imprimir_desembolso(Request $request)
    {

        $param = $request->all();

        try {
            ticketera::imprimir_desembolso(
                $param["fecha_impresion"],
                $param["cliente"],
                $param["descripcion"],
                $param["tipo_gasto"],
                $param["code"],
                $param["recepcionista"],
                $param["copia"],
                $param["monto"],
                $param["para"]
            );

            return response()->json([
                'message' => 'la impresion se ha ejecutado exitosamente',
                'error' => '',
                'success' => true,
                'data' =>  '',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'error del servidor',
                'error' => $th->getMessage(),
                'success' => false,
                'data' => '',
            ]);
        }
    }



    public function impresion_ingresos(Request $request)
    {

        $param = $request->all();

        ticketera::imprimir_ingreso(
            $param["fecha_impresion"],
            $param['saldo_pendiente'],
            $param['monto_cancelado'],
            $param['codigo'],
            $param['cliente'],
            $param['numerosolicitud'],
            $param['analista'],
            $param['recepcionista'],
            $param['total'],
            $param['para']
        );

        return response()->json([
            'message' => 'la impresion se ha ejecutado exitosamente',
            'error' => '',
            'success' => true,
            'data' =>  '',
        ]);
    }



    public function impresion_ingresos_grupal(Request $request)
    {

        $param = $request->all();

        ticketera::imprimir_ingreso_grupal(
            $param["fecha_impresion"],
            $param["cuota"],
            $param["saldo_restante"],
            $param["descripcion"],
            $param['monto_cancelado'],
            $param['codigo'],
            $param['cliente'],
            $param['numerosolicitud'],
            $param['analista'],
            $param['recepcionista'],
            $param['total'],
            $param['para']
        );

        return response()->json([
            'message' => 'la impresion se ha ejecutado exitosamente',
            'error' => '',
            'success' => true,
            'data' =>  '',
        ]);
    }

    public function impresion_voucher_prestamo_cancelado(Request $request)
    {

        try {

            $Datax = $request->all();
            $FechaCreacion = $Datax["FechaCreacion"];
            $Nombres = $Datax["Nombres"];
            $Dni = $Datax["Dni"];
            $NumeroSolicitud = $Datax["NumeroSolicitud"];
            $MCredito = $Datax["MCredito"];
            $Fpago = $Datax["Fpago"];
            $Cuotas = $Datax["Cuotas"];
            $Interes = $Datax["Interes"];
            $InteresDiario = $Datax["InteresDiario"];
            $DiasTranscurrido = $Datax["DiasTranscurrido"];
            $InteresTotal = $Datax["InteresTotal"];
            $MontoRestante = $Datax["MontoRestante"];
            $Total = $Datax["Total"];
            $fecha_inicio = $Datax["fecha_inicio"];
            $fecha_final = $Datax["fecha_final"];

            $nombreImpresora = "XP-80CS";
            $ruta_logo = public_path('dist/images/logo/logo_ticketera.png');
            $ruta_pie = public_path('dist/images/logo/pie_ticketera.png');

            // Conecta con la impresora
            $conector = new WindowsPrintConnector($nombreImpresora);
            $impresora = new Printer($conector);

            $imagen = EscposImage::load($ruta_logo, false);
            $imagen_pie = EscposImage::load($ruta_pie, false);
            // Imprime la imagen
            $impresora->bitImage($imagen);
            $impresora->text("\n");

            $impresora->setEmphasis(true);
            $impresora->setTextSize(2, 1);
            $impresora->text("Cancelación préstamo de la solicitud N° " . $NumeroSolicitud . "\n");
            $impresora->text("\n");

            $impresora->setEmphasis(true);
            $impresora->setTextSize(2, 1);
            $impresora->text("Información de la solicitud\n");
            $impresora->text("\n");

            //////
            $impresora->setEmphasis(true);
            $impresora->setTextSize(1, 1);
            $impresora->text("Nombres y apellidos\n");

            $impresora->setEmphasis(false);
            $impresora->setTextSize(1.3, 1);
            $impresora->text($Nombres . "\n");
            $impresora->text("\n");
            //////


            //////
            $impresora->setEmphasis(true);
            $impresora->setTextSize(1, 1);
            $impresora->text("Dni\n");

            $impresora->setEmphasis(false);
            $impresora->setTextSize(1.3, 1);
            $impresora->text($Dni . "\n");
            $impresora->text("\n");
            //////



            $impresora->setEmphasis(true);
            $impresora->setTextSize(2, 1);
            $impresora->text("Información del préstamo\n");
            $impresora->text("\n");


            $impresora->setEmphasis(false);
            $impresora->setTextSize(1, 1);
            $info_prestamo = [
                ['nombre' => 'Monto solicitado', 'monto' => $MCredito, "is_precio" => true],
                ['nombre' => 'F.pago', 'monto' => $Fpago, "is_precio" => false],
                ['nombre' => 'Cuotas', 'monto' => $Cuotas, "is_precio" => false],
                ['nombre' => 'Interes', 'monto' => $Interes, "is_precio" => false],
            ];
            // Longitud total deseada de la línea
            $total_length = 48;
            foreach ($info_prestamo as $i_p) {
                // Alinea el nombre a la izquierda
                $nombre = $i_p['nombre'];

                // Prepara el monto según si es un precio
                if ($i_p['is_precio']) {
                    $monto = "S/. " . number_format($i_p['monto'], 2);
                } else {
                    $monto = "S/. " . $i_p['monto'];
                }

                // Calcula la longitud total ocupada por el nombre y el monto
                $line_length = strlen($nombre) + strlen($monto);

                // Calcula el espacio necesario para alinear el monto a la derecha
                $espacios = $total_length - $line_length;

                // Imprime el nombre y el monto con los espacios necesarios
                $impresora->text($nombre);
                $impresora->text(str_repeat(" ", $espacios)); // Espacios entre el nombre y el monto
                $impresora->text($monto . "\n");
            }

            $impresora->text("===============================================\n");
            $impresora->setEmphasis(true);
            $impresora->setTextSize(2, 1);
            $impresora->text("Información del pago\n");
            $impresora->text("\n");

            //////
            $impresora->setEmphasis(true);
            $impresora->setTextSize(1, 1);
            $impresora->text("Fecha de la operación\n");

            $impresora->setEmphasis(false);
            $impresora->setTextSize(1.3, 1);
            $impresora->text($FechaCreacion . "\n");
            $impresora->text("\n");
            //////

            //////
            $impresora->setEmphasis(true);
            $impresora->setTextSize(1, 1);
            $impresora->text("Interés diario\n");

            $impresora->setEmphasis(false);
            $impresora->setTextSize(1.3, 1);
            $impresora->text($InteresDiario . "\n");
            $impresora->text("\n");
            //////

            //////
            $impresora->setEmphasis(true);
            $impresora->setTextSize(1, 1);
            $impresora->text("Días trancurridos del " . $fecha_inicio . " al " . $fecha_final . "\n");

            $impresora->setEmphasis(false);
            $impresora->setTextSize(1.3, 1);
            $impresora->text($DiasTranscurrido . "\n");
            $impresora->text("\n");
            ////// 

            //////
            $impresora->setEmphasis(true);
            $impresora->setTextSize(1, 1);
            $impresora->text("Interés total\n");

            $impresora->setEmphasis(false);
            $impresora->setTextSize(1.3, 1);
            $impresora->text($InteresTotal . "\n");
            $impresora->text("\n");
            ////// 

            //////
            $impresora->setEmphasis(true);
            $impresora->setTextSize(1, 1);
            $impresora->text("Monto restante de saldo capital\n");

            $impresora->setEmphasis(false);
            $impresora->setTextSize(1.3, 1);
            $impresora->text($MontoRestante . "\n");
            $impresora->text("\n");
            //////

            //////
            $impresora->setEmphasis(true);
            $impresora->setTextSize(1, 1);
            $impresora->text("Total\n");

            $impresora->setEmphasis(false);
            $impresora->setTextSize(1.3, 1);
            $impresora->text($Total . "\n");
            $impresora->text("\n");
            //////

            $impresora->bitImage($imagen_pie);

            // Finaliza la impresión
            $impresora->feed(1);
            $impresora->cut();
            $impresora->close();

            return response()->json([
                'message' => 'operacion exitosa',
                'error' => '',
                'success' => true,
                'data' => ""
            ]);
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return response()->json([
                'message' => 'error del servidor',
                'error' => $th->getMessage(),
                'success' => false,
                'data' => '',
            ]);
        }
    }


    public function impresion_prueba_cautiva(Request $request)
    {

        try{
             
 
        $pdf = new TCPDF('P', 'mm', array(80, 40), true, 'UTF-8', false);

        // Establecer información del documento
        $pdf->SetCreator('Cautiva');
        $pdf->SetAuthor('');
        $pdf->SetTitle('Ticket');

        // Establecer márgenes
        $pdf->SetMargins(5, 5, 5);

        // Eliminar cabecera y pie de página
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // Establecer resolución DPI más alta (300 DPI)
        $pdf->setImageScale(300 / 72);

        // Agregar una página
        $pdf->AddPage();

        // Establecer fuente
        $pdf->SetFont('helvetica', '', 7);

        // Agregar contenido
        $pdf->Cell(0, 2, 'CAUTIVA', 0, 1, 'C');

        // Establecer estilo del código de barras
        $style = array(
            'position' => '',
            'align' => 'C',
            'stretch' => false,  // Desactivar la distorsión del texto
            'fitwidth' => true,  // Ajustar el código de barras al ancho
            'border' => false, 
            'fgcolor' => array(0, 0, 0), // Color negro
            'bgcolor' => false,  // Fondo transparente
            'text' => true,  // Mostrar texto
            'font' => 'helvetica',
            'fontsize' => 7,  // Aumentar el tamaño de la fuente del texto
            'stretchtext' => 0  // Evitar la distorsión
        );
        // Generar código de barras con un tamaño adecuado
        $pdf->write1DBarcode($request->input("barcode"), 'C128', '', '', '', 15, 10, $style, 'N'); 
        $pdf->SetFont('helvetica', '', 7);
        $pdf->Cell(0, 1,$request->input("product_name"), 0, 1, 'C');
        $pdf->SetFont('helvetica', '', 5);
        $pdf->Cell(0, 1, $request->input("price"), 0, 1, 'C');

        // Ruta completa del archivo en la carpeta public
        $filePath = public_path("files/archivo1.pdf");

        // Asegurarse de que el directorio existe
        if (!file_exists(public_path('files'))) {
            mkdir(public_path('files'), 0755, true);
        }

        // Guardar el PDF en la carpeta public
        $pdf->Output($filePath, 'F');
 

        // Ruta del archivo PDF
        $pdfFile = public_path('files/archivo1.pdf'); // Ajusta la ruta si es necesario

        // Nombre de la impresora
        $printerName = '\\\\DESKTOP-JOV5EM7\\HL3200'; // Asegúrate de que el nombre de la impresora esté bien

        // Ruta del ejecutable de SumatraPDF
        $sumatraPdfPath = '"C:\\programas\\SumatraPDF\\SumatraPDF.exe"'; // Asegúrate de que la ruta del ejecutable sea correcta

        // Comando para imprimir el PDF
        $command = "$sumatraPdfPath -print-to \"$printerName\" \"$pdfFile\"";

        // Ejecutar el comando
        exec($command, $output, $status);

        // Comprobar el resultado
        if ($status === 0) { 
            return response()->json([
                'message' => "Se imprimio correctamente.",
                'error' => "",
                'success' => true,
                'data' => '',
            ]);
        } else { 
            return response()->json([
                'message' => "Hubo un error al imprimir el archivo PDF.",
                'error' => "",
                'success' => false,
                'data' => '',
            ]);
        }

    } catch (\Throwable $th) {
        Log::error($th->getMessage());
        return response()->json([
            'message' => 'error del servidor',
            'error' => $th->getMessage(),
            'success' => false,
            'data' => '',
        ]);
    }
    }
}
