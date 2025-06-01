<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Pedido;
use App\Models\Pago;
use App\Models\DetallePedido;
use App\Models\Cliente; // Asegúrate que exista y esté bien configurado
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB; // Para transacciones si es necesario
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class PagoConQr extends Component
{
    public $mostrarDetalles = true;
    public string $codigoQrSvg;
    public $pedidoGuardado; // Para almacenar el pedido real de la BD
    public $pagoGuardado;   // Para almacenar el pago real de la BD
    public $urlConfirmacion;

    public $carritoProductos;
    public $total;

    public function mount($carritoProductos, $total)
    {
        $this->carritoProductos = $carritoProductos;
        $this->total = $total;
    }

    public function generarPedidoYQrConGuardado()
    {
        $user = Auth::user();

        if (!$user) {
            session()->flash('error_pago_qr', 'Debe iniciar sesión para procesar el pago.');
            return;
        }

        // Obtener el cliente asociado al usuario
        $clienteInstance = $user->cliente; // Esto depende de cómo esté definida tu relación

        // Verificar si $clienteInstance es una colección (si la relación es hasMany)
        if ($clienteInstance instanceof EloquentCollection) {
            // Si es una colección, toma el primer cliente.
            // Ajusta esta lógica si un usuario puede tener múltiples perfiles de cliente
            // y necesitas seleccionar uno específico de alguna manera.
            $cliente = $clienteInstance->first();
        } else {
            // Si no es una colección, se asume que es una instancia de Cliente o null (si la relación es hasOne o belongsTo)
            $cliente = $clienteInstance;
        }

        // Ahora $cliente debería ser una instancia de modelo Cliente o null
        if (!$cliente || !($cliente instanceof Cliente)) { // Doble verificación
            session()->flash('error_pago_qr', 'Perfil de cliente no encontrado o no válido para el usuario.');
            return;
        }

        // Validación (opcional pero recomendada aquí también, aunque sea para desarrollo)
        if (empty($this->carritoProductos) || $this->total <= 0) {
            session()->flash('error_pago_qr', 'El carrito está vacío o el total no es válido.');
            return;
        }

        DB::beginTransaction(); // Iniciar transacción para asegurar consistencia

        try {
            // 1. Crear el Pedido en la BD
            // Ahora puedes usar $cliente->id de forma segura
            $this->pedidoGuardado = Pedido::create([
                'nro_pedido' => 'PED-' . now()->format('YmdHis') . '-' . $cliente->id,
                'estado' => 'Pendiente', // Estado inicial
                'tipo_entrega' => $cliente->preferencia_entrega ?? 'Domicilio',
                'direccion_entrega' => $cliente->direccion_predeterminada ?? $cliente->direccion ?? 'No especificada',
                'notas_adicionales' => '',
                'total' => $this->total,
                'cliente_id' => $cliente->id,
            ]);

            // 2. Crear Detalles del Pedido en la BD
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

            // 3. Crear el registro de Pago en la BD
            $tokenConfirmacion = Str::random(40);
            $this->pagoGuardado = Pago::create([
                'pedido_id' => $this->pedidoGuardado->id,
                'monto_total' => $this->total,
                'estado' => 'Pendiente',
                'token_confirmacion' => $tokenConfirmacion,
            ]);

            // 4. Crear la URL de confirmación que irá en el QR
            $this->urlConfirmacion = route('pago.qr.confirmar', ['token' => $tokenConfirmacion]);

            // 5. Generar el QR Code con la URL de confirmación
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
            // Proporcionar un mensaje de error más descriptivo si es posible, o el original para depuración
            session()->flash('error_pago_qr', 'Ocurrió un error al procesar tu pedido. Inténtalo de nuevo. (' . $e->getMessage() . ')');
            return;
        }
    }

    public function render()
    {
        // En la vista, ahora puedes usar $this->pedidoGuardado y $this->pagoGuardado si necesitas mostrar sus IDs reales, etc.
        return view('livewire.pago-con-qr', [
            'pedidoParaVista' => $this->pedidoGuardado, // Pasa el pedido guardado a la vista
            'pagoParaVista' => $this->pagoGuardado,   // Pasa el pago guardado a la vista
        ]);
    }
}