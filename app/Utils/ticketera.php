<?php
namespace App\Utils;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class ticketera{
    public static function imprimir_ingreso($fecha,$saldo_anterior, $saldo_cancelado, $codigo, $cliente, $n_solicitud, $analista, $recepcionista, $total,$para = ""){

        $fecha_impresion = $fecha;
        
        $nombreImpresora = "XP-80CS";
        $ruta_logo = public_path('dist/images/logo/logo_ticketera.png');
        $ruta_pie = public_path('dist/images/logo/pie_ticketera.png');

        $items = [
            ['nombre' => 'Saldo anterior', 'precio' => $saldo_anterior],
            ['nombre' => 'Saldo cancelado', 'precio' => $saldo_cancelado], 
        ];

        // Conecta con la impresora
        $conector = new WindowsPrintConnector($nombreImpresora);
        $impresora = new Printer($conector);

        $imagen = EscposImage::load($ruta_logo, false);
        $imagen_pie = EscposImage::load($ruta_pie, false);
        // Imprime la imagen
        $impresora->bitImage($imagen);

        // Encabezado de la boleta
        $impresora->text("\n");
        $impresora->text("=== Ticket de pago amortizacion de prestamo === \n");
        $impresora->text("=== Codigo {$codigo}\n");
        $impresora->text("=== Fecha : {$fecha_impresion}\n");
        if($para!=""){
            $impresora->text("=== Documento para => {$para}\n");
        } 
        $impresora->text("===============================================\n");
  

        $impresora->text("Cliente ** {$cliente} \n"); 
        $impresora->text("Numero de solicitud ** {$n_solicitud} \n");
 
        $impresora->text("Recepcionista ** {$recepcionista}\n");
   
        $impresora->text("Analista ** {$analista}\n"); 
        $impresora->text("===============================================\n");
    
        // Detalles de los elementos
        foreach ($items as $item) {
            // Alinea el texto a la izquierda y el precio a la derecha
            $impresora->text($item['nombre']);
            $espacios = 40 - strlen($item['nombre']) - strlen($item['precio']) - substr_count($item['precio'], ",");
            for ($i = 0; $i < $espacios; $i++) {
                $impresora->text(" ");
            }
            $impresora->text("S/." . number_format($item['precio'], 2) . "\n");
        }

        // Total
       
        $impresora->text("===============================================\n");
        
        
        $saldo_restante = [
            ['nombre' => 'saldo restante', 'precio' => $total], 
        ];

        // Detalles de los elementos
        foreach ($saldo_restante as $s_r) {
            // Alinea el texto a la izquierda y el precio a la derecha
            $impresora->text($s_r['nombre']);
            $espacios = 40 - strlen($s_r['nombre']) - strlen($item['precio']) ;
            for ($i = 0; $i < $espacios; $i++) {
                $impresora->text(" ");
            }
            $impresora->text("S/." . number_format($s_r['precio'], 2) . "\n");
        }
        $impresora->bitImage($imagen_pie);

        // Finaliza la impresión
        $impresora->feed(1);
        $impresora->cut();
        $impresora->close();
    }

    public static function imprimir_gasto($fecha,$descripcion, $tipo_cancelado, $codigo, $recepcionista, $total){

        $fecha_impresion = $fecha;
        
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

        // Encabezado de la boleta
        $impresora->text("\n");
        $impresora->text("=== Ticket de gasto === \n");
        $impresora->text("=== Codigo {$codigo}\n");
        $impresora->text("=== Fecha : {$fecha_impresion}\n");
        
        $impresora->text("===============================================\n");
  
 
        $impresora->text("Descripcion ** {$descripcion}\n");
        $impresora->text("Tipo de gasto ** {$tipo_cancelado}\n"); 
        $impresora->text("Usuario ** {$recepcionista}\n"); 

        // Total
     
        $impresora->text("===============================================\n");
        
        
        $saldo_restante = [
            ['nombre' => 'Monto del gasto', 'precio' => $total], 
        ];

        // Detalles de los elementos
        foreach ($saldo_restante as $s_r) {
            // Alinea el texto a la izquierda y el precio a la derecha
            $impresora->text($s_r['nombre']);
            $espacios = 40 - strlen($s_r['nombre']) - strlen($s_r['precio']) ;
            for ($i = 0; $i < $espacios; $i++) {
                $impresora->text(" ");
            }
            $impresora->text("S/." . number_format($s_r['precio'], 2) . "\n");
        }
        $impresora->bitImage($imagen_pie);

        // Finaliza la impresión
        $impresora->feed(1);
        $impresora->cut();
        $impresora->close();
    }
}