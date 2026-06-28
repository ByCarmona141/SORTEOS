@php use Carbon\Carbon; @endphp

@extends('template')

@section('title', 'Dashboard')

@push('css')

@endpush

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Escritorio</h1>
        {{--<h4 class="mt-4">{{ Carbon::now()->subHour(6)->format('d-m-Y') }}</h4>--}}
        <h4 class="mt-4">{{ Carbon::now()->format('d-m-Y') }}</h4>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Escritorio</li>
        </ol>
    </div>
@endsection

@push('css')

@endpush
