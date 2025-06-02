<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\Pago;
use App\Models\Pedido;

class PagoQrController extends Controller
{
    public function mostrarPaginaConfirmacion($token)
    {
        $pago = Pago::where('token_confirmacion', $token)->first();

        if (!$pago) {
            abort(404, 'Solicitud de pago no encontrada o inválida.');
        }

        if ($pago->estado === 'Pagado') {
            $pedido = Pedido::with(['cliente', 'detallePedidos'])->find($pago->pedido_id);
            if (!$pedido) {
                abort(404, 'Pedido asociado no encontrado.');
            }
            return view('pagosqr.confirmado', [
                'pedido' => $pedido,
                'pago' => $pago,
                'mensaje' => 'Este pago ya fue procesado y marcado como Pagado.'
            ]);
        } elseif ($pago->estado !== 'Pendiente') {
            abort(403, 'El estado actual de este pago no permite la confirmación.');
        }
        $pedido = Pedido::with(['cliente', 'detallePedidos'])->find($pago->pedido_id);

        if (!$pedido) {
             abort(404, 'Pedido asociado no encontrado.');
        }

        return view('pagosqr.confirmar', [
            'token' => $token,
            'pedido' => $pedido,
            'pago' => $pago,
        ]);
    }

    public function procesarConfirmacion(Request $request, $token)
    {
        $pago = Pago::where('token_confirmacion', $token)
                    ->where('estado', 'Pendiente')
                    ->first();

        if (!$pago) {
            $pagoYaPagado = Pago::where('token_confirmacion', $token)->where('estado', 'Pagado')->first();
            if ($pagoYaPagado) {
                 return redirect()->route('pago.qr.confirmar', ['token' => $token])
                                 ->with('info_confirmacion', 'Este pago ya ha sido procesado y marcado como Pagado.');
            }
            abort(404, 'Solicitud de pago no encontrada, inválida para procesar o ya procesada.');
        }

        $pedido = Pedido::with(['cliente', 'detallePedidos'])->find($pago->pedido_id);
        if (!$pedido) {
            abort(500, 'Error: No se encontró el pedido asociado al pago.');
        }
        $pago->estado = 'Pagado';
        $pago->save();

        $pedido->estado = 'Confirmado';
        $pedido->save();

        Session::forget('carrito');

        session()->flash('pago_qr_completado_flag', true);

        return view('pagosqr.confirmado', [ 
            'pedido' => $pedido,
            'pago' => $pago,
            'mensaje' => '¡Pago confirmado y marcado como Pagado exitosamente!'
        ]);
    }
}