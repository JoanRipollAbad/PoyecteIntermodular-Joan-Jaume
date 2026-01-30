@extends('layouts.app')

@section('title', 'Pujar Fitxer Excel')

@section('styles')
    <style>
        /* Estilos básicos para centrar el formulario de subida */
        .upload-container {
            max-width: 600px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .upload-form input[type="file"] {
            margin: 20px 0;
            padding: 10px;
            border: 2px dashed #7ed9c7;
            width: 100%;
            border-radius: 10px;
        }
        .btn-upload {
            background-color: #000;
            color: white;
            padding: 10px 25px;
            border: none;
            border-radius: 30px;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn-upload:hover {
            background-color: #333;
        }
    </style>
@endsection

@section('content')
<div class="container">
    <div class="upload-container">
        <h1>Pujar Fitxer Excel</h1>
        <p class="text-muted">Formats acceptats: .xlsx, .xls, .csv</p>

        {{-- Formulario de Laravel --}}
        <form action="{{ url('/upload') }}" method="POST" enctype="multipart/form-data" class="upload-form">
            @csrf {{-- Token de seguridad obligatorio --}}
            
            <div class="form-group">
                <label for="fitxer" style="font-weight: bold;">Tria un fitxer del teu ordinador:</label>
                <input type="file" name="fitxer" id="fitxer" accept=".xlsx,.xls,.csv" required />
            </div>

            <br>
            <button type="submit" class="btn-upload">Pujar Fitxer</button>
        </form>
    </div>
</div>
@endsection