<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Rol; // Importa tu modelo Rol
use Livewire\WithPagination;

class RoleManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $sortBy = 'id';
    public $sortDirection = 'asc';

    public $editingRole = null;
    public $form = [
        'nombre' => '',
        'descripcion' => '', // Añadimos la propiedad para la descripción
    ];
    public $showRoleForm = false;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $roles = Rol::query()
            ->when($this->search, function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->search . '%'); // Buscar también por descripción
            })
            ->orderBy($this->sortBy, $this->sortDirection)
            ->paginate(10);

        return view('livewire.role-management', [
            'roles' => $roles,
        ]);
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function createRole()
    {
        $this->reset(['editingRole', 'form']); // Limpiar el formulario
        $this->showRoleForm = true;
    }

    public function closeRoleForm()
    {
        $this->showRoleForm = false;
        $this->resetValidation(); // Limpia los errores de validación
    }

    public function saveRole()
    {
        $rules = [
            'form.nombre' => 'required|string|max:255|unique:roles,nombre,' . ($this->editingRole ? $this->editingRole->id : 'NULL'),
            'form.descripcion' => 'nullable|string|max:1000', // Validación para la descripción
        ];

        $this->validate($rules);

        if ($this->editingRole) {
            // Actualizar rol existente
            $this->editingRole->update($this->form);
            session()->flash('message', 'Rol actualizado exitosamente.');
        } else {
            // Crear nuevo rol
            Rol::create($this->form);
            session()->flash('message', 'Rol creado exitosamente.');
        }

        $this->closeRoleForm();
        $this->resetPage();
    }

    public function editRole(Rol $rol)
    {
        $this->editingRole = $rol;
        $this->form = $rol->toArray(); // Cargar los datos del rol en el formulario
        $this->showRoleForm = true;
    }

    public function deleteRole(Rol $rol)
    {
        // Antes de eliminar, una buena práctica sería verificar si hay usuarios asociados.
        // Si quieres impedir la eliminación de roles con usuarios, descomenta y ajusta esto:
        /*
        if ($rol->usuarios()->exists()) {
            session()->flash('error', 'No se puede eliminar el rol porque tiene usuarios asociados. Reasigna los usuarios primero.');
            return;
        }
        */

        $rol->delete();
        session()->flash('message', 'Rol eliminado exitosamente.');
    }
}