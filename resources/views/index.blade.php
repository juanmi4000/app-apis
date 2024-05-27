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
        /* Estilos CSS personalizados */

        /* Estilos para el calendario */
        #calendar {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
        }

        .fc-toolbar {
            margin-bottom: 20px;
        }

        .fc-toolbar-title {
            font-size: 1.5em;
            margin-right: 20px;
        }

        .fc-button {
            background-color: #007bff;
            border-color: #007bff;
        }

        .fc-button:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .fc-button-primary {
            background-color: #007bff;
            border-color: #007bff;
        }

        .fc-button-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .fc-button-group {
            margin-right: 20px;
        }

        .fc-day-header {
            font-weight: bold;
            font-size: 0.9em;
        }

        .fc-day {
            border: 1px solid #ddd;
            padding: 5px;
        }

        .fc-today {
            background-color: #f0f0f0;
        }

        .fc-event {
            background-color: #007bff;
            border-color: #007bff;
            color: #fff;
        }

        .fc-event:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }

        .fc-event-title {
            font-weight: bold;
        }

        .fc-prev-button,
        .fc-next-button {
            font-size: 1.2em;
        }
    </style>
    
</head>

<body class="font-sans antialiased">

    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Navbar scroll</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll"
                    aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarScroll">
                    <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                        <li class="nav-item">
                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Link</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                Link
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link disabled" aria-disabled="true">Link</a>
                        </li>
                    </ul>
                    <a class="nav-link">
                        Nuevo Post
                    </a>
                </div>
            </div>
        </nav>
    </header>
    <div id='calendar'></div>

    <div class="container">
        <h1>Subir Imágenes a Instagram</h1>
        <form action="{{ route('subir_imagen') }}" method="post" enctype="multipart/form-data">
            @csrf
            <label for="imagen">Seleccionar imagen:</label>
            <input type="file" name="imagen" id="imagen" accept="image/*" required>
            <label for="comentario">Comentario (opcional):</label>
            <textarea name="comentario" id="comentario" rows="4" placeholder="Escribe un comentario aquí"></textarea>
            <button type="submit">Subir Imagen</button>
        </form>
        
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="container2">
        <h1>Imágenes de Instagram</h1>
        <div class="gallery">
            <?php
                /* @foreach ($images as $image)
                    <div class="image"><img src="{{ $image }}" alt="Imagen de Instagram"></div>
                @endforeach */
            ?>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth'
            });
            calendar.render();
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>


</html>
