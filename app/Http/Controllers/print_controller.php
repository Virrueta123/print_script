<?php

namespace App\Http\Controllers;

use App\Utils\ticketera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Milon\Barcode\DNS1D;
use Picqer\Barcode\BarcodeGeneratorPNG;

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
            // Configuración de la impresora
            $nombreImpresora = "EEPRINT";
            $conector = new WindowsPrintConnector($nombreImpresora);
            $impresora = new Printer($conector);

            // Configurar el estilo de texto
            $impresora->setEmphasis(true);
            $impresora->setFont(Printer::FONT_B);
 
            // Centrar el contenido
            $impresora->setJustification(Printer::JUSTIFY_CENTER);

            // Imprimir texto descriptivo
            $impresora->text("Producto: CAUTIVA\n");
            $impresora->feed(1);
            $impresora->cut();
            // Cerrar la conexión
            $impresora->close();


            return response()->json([
                'message' => 'Impresión exitosa',
                'error' => "",
                'success' => true,
                'data' => '',
            ]);
        } catch (\Throwable $th) {
            Log::error('Error en impresión de código EAN13: ' . $th->getMessage());

            return response()->json([
                'message' => 'Error del servidor',
                'error' => $th->getMessage(),
                'success' => false,
                'data' => '',
            ]);
        }
    }
}
