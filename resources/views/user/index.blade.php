@extends('template')

@section('title', 'Usuarios')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">

        <h1 class="mt-4">Usuarios</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Usuarios</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Tabla Usuarios
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection

@push('js')

@endpush
