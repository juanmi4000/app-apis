<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-b from-transparent via-white to-white">
        <form action="{{ route('subir-imagen') }}" method="POST" enctype="multipart/form-data" class="p-6 bg-white rounded-lg shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)]">
            @csrf
            <div class="mb-4">
                <label for="imagen" class="text-xl font-semibold">Seleccionar imagen:</label>
                <input type="file" name="imagen" id="imagen" class="block w-full py-2 mt-2 rounded-md shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)]" accept="image/*" required>
            </div>
            <div class="mb-4">
                <label for="comentario" class="text-xl font-semibold">Comentario (opcional):</label>
                <textarea name="comentario" id="comentario" rows="4" class="block w-full py-2 mt-2 rounded-md shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)]" placeholder="Escribe un comentario aquí"></textarea>
            </div>
            <button type="submit" class="px-6 py-2 bg-black text-white font-semibold rounded-md hover:bg-gray-800">Subir Imagen</button>
        </form>
    </div>
</body>
</html>
