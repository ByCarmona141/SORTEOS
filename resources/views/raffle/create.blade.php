@extends('template')

@section('title', 'Crear Sorteo')

@push('css')

@endpush

@section('content')

    <div class="container-fluid px-4">
        <h1 class="mt-4">Crear Nuevo Sorteo</h1>

        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="{{route('dashboard')}}">Inicio</a></li>
            <li class="breadcrumb-item"><a href="{{route('raffle.index')}}">Sorteos</a></li>
            <li class="breadcrumb-item active">Crear Sorteo</li>
        </ol>

        <div class="container w-100 border border-3 border-primary rounded p-4 mt-3">
            <form action="{{route('raffle.store')}}" method="post" autocomplete="off">
                @csrf
                <div class="row g-3">

                    <!-- name -->
                    <x-form-element id="name" label="Nombre" required="true" focused="true"></x-form-element>

                    <!-- description -->
                    <x-form-element id="description" label="Descripción" type="textarea"></x-form-element>

                    <!-- ticket_count -->
                    <x-form-element id="ticket_count" label="Cantidad de boletos" required="true"
                                    colSize="6"></x-form-element>

                    <!-- ticket_price -->
                    <x-form-element id="ticket_price" label="Precio del boleto" required="true"
                                    colSize="6"></x-form-element>

                    <!-- draw_date -->
                    <x-form-element id="draw_date" label="Fecha del Sorteo" type="date"></x-form-element>

                    <!-- Opportunities -->
                    <x-form-element id="opportunities" label="Oportunidades" required="true"></x-form-element>

                    <!-- Form buttons -->
                    <x-form-buttons routeName="raffle"></x-form-buttons>

                </div>
            </form>
        </div>
    </div>

@endsection

@push('js')

@endpush
