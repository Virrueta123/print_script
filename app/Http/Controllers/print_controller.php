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

        try {
            // Create new PDF document (A4 size)
            $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

            // Set document information
            $pdf->SetCreator('Cautiva');
            $pdf->SetAuthor('');
            $pdf->SetTitle('Ticket');

            // Remove default header and footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // Set image scale factor (increased for better quality)
            $pdf->setImageScale(600 / 72);

            // Add a page
            $pdf->AddPage();

            // Set font (increased size for better quality and file size)
            $pdf->SetFont('helvetica', '', 9);

            // Define the content area (40x30mm in the top-left corner)
            $pdf->SetXY(10, 10); // 10mm from left, 10mm from top

            // Create a clipping area
            $pdf->StartTransform();
            $pdf->Rect(10, 10, 40, 30, 'CNZ');

            // Add content within the clipping area
            $pdf->SetTextColor(0, 0, 0);
            $pdf->MultiCell(40, 5, 'CAUTIVA', 0, 'C', false, 1, '', '', true, 0, false, true, 5, 'M');

            // Generate barcode
            $style = array(
                'position' => '',
                'align' => 'C',
                'stretch' => false,
                'fitwidth' => true,
                'border' => false,
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => false,
                'text' => true,
                'font' => 'helvetica',
                'fontsize' => 8,
                'stretchtext' => 0
            );

            // Adjust barcode size to fit within the 40x30mm area
            $pdf->write1DBarcode($request->input("barcode"), 'C128', '', '', 38, 13, 0.4, $style, 'N');

            $pdf->SetXY(10, 32);
            $pdf->MultiCell(40, 3, $request->input("product_name"), 0, 'C', false, 1, '', '', true, 0, false, true, 3, 'M');

            $pdf->SetXY(10, 35);
            $pdf->MultiCell(40, 3, $request->input("price"), 0, 'C', false, 1, '', '', true, 0, false, true, 3, 'M');

            // End the clipping area
            $pdf->StopTransform();

            // Set PDF compression level (adjusted for better quality and file size)
            $pdf->SetCompression(false);
            $pdf->setJPEGQuality(100);

            // Add some invisible content to increase file size
            for ($i = 0; $i < 100; $i++) {
                $pdf->SetXY(rand(50, 200), rand(50, 280));
                $pdf->SetTextColor(255, 255, 255);
                $pdf->Cell(10, 10, 'X', 0, 0, 'C');
            }

            // Output the PDF
            $filePath = public_path("files/archivo1.pdf");

            // Ensure the directory exists
            if (!file_exists(public_path('files'))) {
                mkdir(public_path('files'), 0755, true);
            }

            // Save the PDF
            $pdf->Output($filePath, 'F');

            // Check file size and adjust if necessary
            $fileSize = filesize($filePath);
            $targetSize = 57 * 1024; // 57KB in bytes

            if ($fileSize < $targetSize) {
                // If file is too small, add some padding
                $padding = str_repeat('0', $targetSize - $fileSize);
                file_put_contents($filePath, $padding, FILE_APPEND);
            }

            // Ruta completa del archivo en la carpeta public
            $filePath = public_path("files/archivo1.pdf");

            // Asegurarse de que el directorio existe
            if (!file_exists(public_path('files'))) {
                mkdir(public_path('files'), 0755, true);
            }

            // Guardar el PDF en la carpeta public
            $pdf->Output($filePath, 'F');

            exit;

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
