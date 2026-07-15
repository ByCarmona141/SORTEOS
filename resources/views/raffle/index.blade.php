@extends('template')

@section('title', 'Sorteos')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Sorteos</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Sorteos</li>
        </ol>

        <div class="mb-4">
            <a href="{{route('raffle.create')}}" class="btn btn-success">Nuevo</a>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Listado de sorteos
            </div>
            <div class="card-body">
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                    <tr>
                        <th>id</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Cantidad de boletos</th>
                        <th>Precio por boleto</th>
                        <th>Oportunidades</th>
                        <th>Fecha del sorteo</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($raffles as $raffle)
                        <tr>
                            <!-- id -->
                            <td>{{ $raffle->id }}</td>
                            <!-- name -->
                            <td>{{ $raffle->name }}</td>
                            <!-- description -->
                            <td>{{ $raffle->description }}</td>
                            <!-- ticket_count -->
                            <td>{{ $raffle->ticket_count }}</td>
                            <!-- ticket_price -->
                            <td>${{ number_format($raffle->ticket_price, 2) }}</td>
                            <!-- opportunities -->
                            <td>{{ $raffle->opportunities }}</td>
                            <!-- draw_date -->
                            <td>
                                {{ $raffle->draw_date == null ? '' : \Carbon\Carbon::parse($raffle->draw_date)->format('d-m-Y') }}
                            </td>
                            <!-- status -->
                            <td>{{ $raffle->status }}</td>
                            <!-- Action buttons -->
                            <x-action-buttons routeName="raffle" :params="$raffle"></x-action-buttons>
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
