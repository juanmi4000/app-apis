<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Laravel</title>
		<link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
		<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
	</head>
	<body class="font-sans antialiased">
		<header>
			<nav class="navbar navbar-expand-lg bg-body-tertiary">
				<div class="container-fluid">
					<p class="navbar-brand">Aplicación</p>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav me-auto mb-2 mb-lg-0">
							<li class="nav-item">
								<a class="nav-link active" aria-current="page" href="#">Opción 1</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">Link</a>
							</li>
							<li class="nav-item dropdown">
								<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
									Dropdown
								</a>
								<ul class="dropdown-menu">
									<li>
										<a class="dropdown-item" href="#">Action</a>
									</li>
									<li>
										<a class="dropdown-item" href="#">Another action</a>
									</li>
									<li><hr class="dropdown-divider"></li>
									<li>
										<a class="dropdown-item" href="#">Something else here</a>
									</li>
								</ul>
							</li>
							<li class="nav-item">
								<a class="nav-link disabled" aria-disabled="true">Disabled</a>
							</li>
						</ul>
					</div>
				</div>
			</nav>
		</header>
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
		@yield('section')
	</body>
</html>

