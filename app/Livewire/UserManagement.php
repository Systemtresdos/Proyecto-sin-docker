<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Usuario; // Importa tu modelo Usuario
use Livewire\WithPagination; // Para paginación, si la necesitas

class UserManagement extends Component
{
    use WithPagination; // Habilita la paginación

    // Propiedades para buscar y ordenar (opcional, para futuras mejoras)
    public $search = '';
    public $sortBy = 'id';
    public $sortDirection = 'asc';

    // Propiedades para el formulario de crear/editar usuario (las veremos después)
    public $editingUser = null; // Para almacenar el usuario que se está editando
    public $form = [
        'nombre' => '',
        'telefono' => '',
        'direccion' => '',
        'email' => '',
        'password' => '',
        'estado' => 'Inactivo', // Valor por defecto
        'rol_id' => null,
    ];
    public $showUserForm = false; // Para mostrar/ocultar el formulario (modal o sección)

    // Resetear la paginación cuando se cambia la búsqueda
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Obtener todos los roles para el select de roles (necesitas un modelo Rol y una tabla roles)
        // Asumo que tienes un modelo Rol y una tabla roles para la foreign key
        $roles = \App\Models\Rol::all(); // Asegúrate de que este modelo exista y esté importado

        $usuarios = Usuario::query()
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('telefono', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10); // Pagina de 10 en 10 usuarios

        return view('livewire.user-management', [
            'usuarios' => $usuarios,
            'roles' => $roles, // Pasar los roles a la vista
        ]);
    }

    // Métodos para ordenar (opcional, para futuras mejoras)
    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    // Método para mostrar el formulario de creación
    public function createUser()
    {
        $this->reset(['editingUser', 'form']); // Limpiar el formulario
        $this->showUserForm = true;
    }

    // Método para cerrar el formulario
    public function closeUserForm()
    {
        $this->showUserForm = false;
    }

    // Método para guardar un nuevo usuario o actualizar uno existente
    public function saveUser()
    {
        $this->validate([
            'form.nombre' => 'required|string|max:255',
            'form.telefono' => 'nullable|string|max:20|unique:usuarios,telefono,' . ($this->editingUser ? $this->editingUser->id : 'NULL'),
            'form.direccion' => 'required|string|max:255',
            'form.email' => 'required|email|max:255|unique:usuarios,email,' . ($this->editingUser ? $this->editingUser->id : 'NULL'),
            'form.password' => $this->editingUser ? 'nullable|string|min:8' : 'required|string|min:8', // Password es requerido solo al crear
            'form.estado' => 'required|in:Activo,Inactivo,Bloqueado',
            'form.rol_id' => 'required|exists:roles,id',
        ]);

        if ($this->editingUser) {
            // Actualizar usuario existente
            $this->editingUser->nombre = $this->form['nombre'];
            $this->editingUser->telefono = $this->form['telefono'];
            $this->editingUser->direccion = $this->form['direccion'];
            $this->editingUser->email = $this->form['email'];
            if (!empty($this->form['password'])) {
                $this->editingUser->password = \Illuminate\Support\Facades\Hash::make($this->form['password']);
            }
            $this->editingUser->estado = $this->form['estado'];
            $this->editingUser->rol_id = $this->form['rol_id'];
            $this->editingUser->save();

            session()->flash('message', 'Usuario actualizado exitosamente.');
        } else {
            // Crear nuevo usuario
            Usuario::create([
                'nombre' => $this->form['nombre'],
                'telefono' => $this->form['telefono'],
                'direccion' => $this->form['direccion'],
                'email' => $this->form['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($this->form['password']),
                'estado' => $this->form['estado'],
                'rol_id' => $this->form['rol_id'],
            ]);
            session()->flash('message', 'Usuario creado exitosamente.');
        }

        $this->closeUserForm(); // Cerrar el formulario después de guardar
        $this->resetPage(); // Reiniciar paginación para ver el nuevo usuario
    }

    // Método para cargar los datos de un usuario para edición
    public function editUser(Usuario $usuario)
    {
        $this->editingUser = $usuario;
        $this->form = $usuario->toArray(); // Cargar los datos del usuario en el formulario
        $this->form['password'] = ''; // No mostrar el password en el formulario de edición
        $this->showUserForm = true;
    }

    // Método para eliminar un usuario
    public function deleteUser(Usuario $usuario)
    {
        $usuario->delete();
        session()->flash('message', 'Usuario eliminado exitosamente.');
    }
}