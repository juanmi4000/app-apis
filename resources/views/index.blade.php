<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy"
        crossorigin="anonymous"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.10.0/main.min.js'></script>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            padding-top: 20px;
            display: flex;
            justify-content: space-around;
        }

        .container {
            width: 45%;
            margin: 0 auto;
        }

        .container.calendar {
            order: 1;
        }

        .container.form {
            order: 2;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="file"] {
            display: block;
            margin-top: 5px;
        }

        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .alert {
            margin-top: 20px;
        }

        .gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            grid-gap: 20px;
        }

        .image img {
            width: 100%;
            height: auto;
        }

        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            background-color: #fff;
        }

        /* Ocultar la segunda sección por defecto */
        .second-section {
            display: none;
        }
    </style>
</head>

<body class="font-sans antialiased">
    @include('layout.header')

    <div class="container">
        <h1>Selecciona una fecha y hora para programar tu publicación en Instagram:</h1>
        <div id="datetime-picker">
            <label for="date">Fecha:</label>
            <input type="date" id="date">
            <label for="time">Hora:</label>
            <input type="time" id="time">
            <!-- Agregar un botón para mostrar la segunda sección del formulario -->
            <button onclick="showSecondSection()">Siguiente</button>
        </div>

        <!-- Segunda sección del formulario (para cargar la imagen y el texto opcional) -->
        <div id="second-section" class="second-section">
            <form action="{{ route('upload.image') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="imagen">Selecciona una imagen:</label>
                    <input type="file" id="imagen" name="imagen">
                </div>
                <div class="form-group">
                    <label for="descripcion">Descripción (opcional):</label>
                    <textarea id="descripcion" name="descripcion" rows="4"></textarea>
                </div>
                <button type="submit">Subir Imagen</button>
            </form>
        </div>
    </div>

    <script>
        // Función para mostrar la segunda sección del formulario cuando se selecciona la fecha y hora
        function showSecondSection() {
            document.getElementById('second-section').style.display = 'block';
        }
    </script>
</body>

</html>
