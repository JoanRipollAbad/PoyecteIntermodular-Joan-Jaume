<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Subir Inventario</title>
</head>
<body>
    <h1>Subir Excel de Productos</h1>
    <form action="{{ url('/upload') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="fitxer" accept=".xlsx,.xls,.csv" required />
        <button type="submit">Subir Archivo</button>
    </form>
</body>
</html>