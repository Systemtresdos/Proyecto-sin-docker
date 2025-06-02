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

    public function generarPedidoYQrConGuardado()
    {
        $usuario = Auth::user();

        if (!$usuario) {
            session()->flash('error_pago_qr', 'Debe iniciar sesión para procesar el pago.');
            return;
        }

        $clienteInstance = $usuario->cliente;

        if ($clienteInstance instanceof EloquentCollection) {
            $cliente = $clienteInstance->first();
        } else {
            $cliente = $clienteInstance;
        }

        if (!$cliente || !($cliente instanceof Cliente)) {
            session()->flash('error_pago_qr', 'Perfil de cliente no encontrado o no válido para el usuario.');
            return;
        }

        if (empty($this->carritoProductos) || $this->total <= 0) {
            session()->flash('error_pago_qr', 'El carrito está vacío o el total no es válido.');
            return;
        }

        DB::beginTransaction();
        try {
            $this->pedidoGuardado = Pedido::create([
                'nro_pedido' => 'PED-' . now()->format('Ymd') . '-' . $cliente->id,
                'estado' => 'Pendiente',
                'tipo_entrega' => $this->tipo_entrega ?? 'Domicilio',
                'direccion_entrega' => $cliente->usuario->direccion ?? $this->direccion_entrega ?? 'No especificada',
                'notas_adicionales' => $this->notas_adicionales ?? '',
                'metodo_pago' => $this->metodo_pago ?? 'Qr',
                'total' => $this->total,
                'cliente_id' => $cliente->id,
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

            $tokenConfirmacion = Str::random(40);
            $this->pagoGuardado = Pago::create([
                'pedido_id' => $this->pedidoGuardado->id,
                'monto_total' => $this->total,
                'estado' => 'Pendiente',
                'token_confirmacion' => $tokenConfirmacion,
            ]);

            $this->urlConfirmacion = route('pago.qr.confirmar', ['token' => $tokenConfirmacion]);

            $qrImageSvgString = QrCode::format('svg')
                ->size(250)
                ->errorCorrection('M')
                ->generate($this->urlConfirmacion);

            $this->codigoQrSvg = $qrImageSvgString;
            $this->mostrarDetalles = false;

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error('Error al generar pedido y QR: ' . $e->getMessage(), ['exception' => $e]);
            session()->flash('error_pago_qr', 'Ocurrió un error al procesar tu pedido. Inténtalo de nuevo. (' . $e->getMessage() . ')');
            return;
        }
    }

    public function render()
    {
        if($this->tipo_entrega === 'Domicilio') {
            $this->direccion_entrega = $this->direccion_entrega ?: Auth::user()->cliente->usuario->direccion ?? '';
        } elseif($this->tipo_entrega === 'Retiro_local') {
            $this->direccion_entrega = 'Retiro en local';
        } 
        return view('livewire.pago-con-qr', [
            'pedidoParaVista' => $this->pedidoGuardado, 
            'pagoParaVista' => $this->pagoGuardado,
        ]);
    }
}