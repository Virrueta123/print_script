<?php

namespace App\Http\Controllers;

use App\Utils\ticketera;
use Illuminate\Http\Request;

class print_controller extends Controller
{
    public function impresion_prueba()
    {
        dd("recepcionista");
    }

    public function impresion_gastos( Request $request )
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



    public function impresion_ingresos( Request $request )
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

     public function impresion_ingresos_grupal( Request $request )
    {

        $param = $request->all();
  
        ticketera::imprimir_ingreso_grupal(
            $param["fecha_impresion"],
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
}
