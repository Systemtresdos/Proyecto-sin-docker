<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Pedido;
use App\Models\Pago;
use App\Models\DetallePedido;
use App\Models\Cliente;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\Session;

class PagoConQr extends Component
{
    public $mostrarDetalles = true;
    public string $codigoQrSvg;
    public $pedidoGuardado;
    public $pagoGuardado;
    public $urlConfirmacion;
    public $carritoProductos;
    public $total;

    public $tipo_entrega = '';
    public $direccion_entrega = '';
    public $notas_adicionales = '';
    public $metodo_pago = '';

    public function mount($carritoProductos, $total)
    {
        $this->carritoProductos = $carritoProductos;
        $this->total = $total;
    }

    public function procesarPedido()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            session()->flash('error_pago_qr', 'Debe iniciar sesión para procesar el pago.');
            return;
        }

        $esPedidoEnLocalPorAdmin = isset($usuario->rol_id) && $usuario->rol_id == 1;

        $clienteIdParaPedido = null;
        $nroPedidoIdentificador = '';
        $tipoEntregaParaPedido = $this->tipo_entrega;
        $direccionEntregaParaPedido = $this->direccion_entrega;
        $metodoPagoParaPedido = $this->metodo_pago;

        if ($esPedidoEnLocalPorAdmin) {

            $clienteIdParaPedido = null;
            $nroPedidoIdentificador = 'LOCAL-' . $usuario->id;
            $tipoEntregaParaPedido = 'Retiro_local'; 
            $direccionEntregaParaPedido = 'Pedido en local';
            if (empty($metodoPagoParaPedido)) {
                session()->flash('error_pago_qr', 'Debe seleccionar un método de pago.');
                return;
            }
        } else {
            $clienteInstance = $usuario->cliente;
            $cliente = null;

            if ($clienteInstance instanceof EloquentCollection) {
                $cliente = $clienteInstance->first();
            } elseif ($clienteInstance instanceof Cliente) {
                $cliente = $clienteInstance;
            }

            if (!$cliente || !($cliente instanceof Cliente)) {
                session()->flash('error_pago_qr', 'Perfil de cliente no encontrado o no válido para el usuario.');
                return;
            }

            $clienteIdParaPedido = $cliente->id;
            $nroPedidoIdentificador = (string)$cliente->id;

            $tipoEntregaParaPedido = $this->tipo_entrega ?? 'Retiro_local';
            if ($tipoEntregaParaPedido === 'Domicilio') {
                $direccionEntregaParaPedido = $cliente->usuario->direccion ?? $this->direccion_entrega ?? '';
                if (empty($direccionEntregaParaPedido)) {
                    session()->flash('error_pago_qr', 'La dirección de entrega es requerida para envío a domicilio.');
                    return;
                }
            } elseif ($tipoEntregaParaPedido === 'Retiro_local') {
                $direccionEntregaParaPedido = 'Retiro en local';
            } else {
                $direccionEntregaParaPedido = $this->direccion_entrega ?? 'No especificada';
            }
            $metodoPagoParaPedido = $this->metodo_pago ?? 'Qr';
        }

        if (empty($this->carritoProductos) || $this->total <= 0) {
            session()->flash('error_pago_qr', 'El carrito está vacío o el total no es válido.');
            return;
        }

        DB::beginTransaction();
        try {
            $validate = $this->validate([
                'tipo_entrega' => ['required', 'in:Domicilio,Retiro_local'],
                'direccion_entrega' => ['required','string', 'max:255'],
                'metodo_pago' => ['required', 'in:Qr,Efectivo'],
            ]);

            $this->pedidoGuardado = Pedido::create([
                'nro_pedido' => 'PED-' . now()->format('Ymd') . '-' . $nroPedidoIdentificador,
                'estado' => 'Pendiente',
                'tipo_entrega' => $tipoEntregaParaPedido,
                'direccion_entrega' => $direccionEntregaParaPedido,
                'notas_adicionales' => $this->notas_adicionales ?? '',
                'metodo_pago' => $metodoPagoParaPedido,
                'total' => $this->total,
                'cliente_id' => $clienteIdParaPedido,
            ]);

            foreach ($this->carritoProductos as $productoId => $item) {
                DetallePedido::create([
                    'pedido_id' => $this->pedidoGuardado->id,
                    'producto_id' => $item['producto_id'] ?? $productoId,
                    'nombre_producto' => $item['nombre'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio_venta'] ?? $item['precio'],
                    'subtotal' => ($item['precio_venta'] ?? $item['precio']) * $item['cantidad'],
                ]);
            }

            if ($metodoPagoParaPedido === 'Qr') {
                $tokenConfirmacion = Str::random(40);
                $this->pagoGuardado = Pago::create([
                    'pedido_id' => $this->pedidoGuardado->id,
                    'monto_total' => $this->total,
                    'estado' => 'Pendiente',
                    'token_confirmacion' => $tokenConfirmacion,
                ]);

                $this->urlConfirmacion = route('pago.qr.confirmar', ['token' => $tokenConfirmacion]);
                $this->codigoQrSvg = QrCode::format('svg')
                    ->size(250)
                    ->errorCorrection('M')
                    ->generate($this->urlConfirmacion);
                $this->mostrarDetalles = false;
            } elseif ($esPedidoEnLocalPorAdmin) {
                $this->pedidoGuardado->estado = 'Confirmado';
                $this->pedidoGuardado->save();

                $this->pagoGuardado = Pago::create([
                    'pedido_id' => $this->pedidoGuardado->id,
                    'monto_total' => $this->total,
                    'estado' => 'Pagado',
                ]);
                $this->codigoQrSvg = '';
                $this->mostrarDetalles = false;
            } else {
                $this->mostrarDetalles = false;
            }

            DB::commit();

            Session::forget('carrito');
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error al generar pedido y QR: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error_pago_qr', 'Ocurrió un error al procesar tu pedido. Inténtalo de nuevo. (' . $e->getMessage() . ')');
            return;
        }
    }

    public function render()
    {
        $usuario = Auth::user();
        // Solo ajustar dirección para clientes normales basados en tipo de entrega seleccionado en el form
        if ($usuario && ! (isset($usuario->rol_id) && $usuario->rol_id == 1)) {
            if ($this->tipo_entrega === 'Domicilio') {
                // Asegurarse que $usuario->cliente y $usuario->cliente->usuario existen
                if ($usuario->cliente && $usuario->cliente->usuario) {
                    $this->direccion_entrega = $usuario->cliente->usuario->direccion ?? $this->direccion_entrega ?? '';
                } else {
                     // Si no hay cliente o dirección de usuario, se usará lo que esté en $this->direccion_entrega
                }
            } elseif ($this->tipo_entrega === 'Retiro_local') {
                $this->direccion_entrega = 'Retiro en local';
            }
        }
        // Para rol_id = 1, tipo_entrega y direccion_entrega se fijan en procesarPedido
        // y no deberían cambiar dinámicamente desde la vista si son fijos.
        // La vista debería deshabilitar estos campos para rol_id = 1.
        
        return view('livewire.pago-con-qr', [
            'pedidoParaVista' => $this->pedidoGuardado, 
            'pagoParaVista' => $this->pagoGuardado,
        ]);
    }
}