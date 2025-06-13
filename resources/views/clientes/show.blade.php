@extends('layouts.app')

@section('title', $cliente->nombre . ' - SIG Turístico')

@section('content')
<div class="row">
    <!-- Información del punto -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Información</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                        <i class="fas fa-cog"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('clientes.edit', $cliente) }}">
                            <i class="fas fa-edit me-2"></i>Editar
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="eliminarPunto()">
                            <i class="fas fa-trash me-2"></i>Eliminar
                        </a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <!-- Imagen -->
                @if($cliente->imagen)
                <div class="mb-3">
                    <img src="{{ asset('storage/' . $cliente->imagen) }}" 
                         alt="{{ $cliente->nombre }}" 
                         class="img-fluid rounded"
                         style="width: 100%; height: 200px; object-fit: cover;">
                </div>
                @else
                <div class="mb-3">
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                         style="height: 200px;">
                        <i class="fas fa-image fa-3x text-muted"></i>
                    </div>
                </div>
                @endif

                <!-- Nombre -->
                <h4 class="mb-3">{{ $cliente->nombre }}</h4>

                <!-- Categoría -->
                <div class="mb-3">
                    <label class="fw-bold">Categoría:</label>
                    <br>
                    <span class="categoria-badge {{ $cliente->categoria }}">
                        <i class="fas fa-tag me-1"></i>{{ ucfirst($cliente->categoria) }}
                    </span>
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label class="fw-bold">Descripción:</label>
                    <p class="mt-2">{{ $cliente->descripcion }}</p>
                </div>

                <!-- Coordenadas -->
                <div class="mb-3">
                    <label class="fw-bold">Coordenadas:</label>
                    <div class="mt-2">
                        <small class="text-muted">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            Lat: {{ $cliente->latitud }}, Lng: {{ $cliente->longitud }}
                        </small>
                        <br>
                        <button class="btn btn-sm btn-outline-primary mt-2" onclick="copiarCoordenadas()">
                            <i class="fas fa-copy me-1"></i>Copiar Coordenadas
                        </button>
                    </div>
                </div>

                <!-- Fecha de creación -->
                <div class="mb-3">
                    <label class="fw-bold">Agregado:</label>
                    <br>
                    <small class="text-muted">
                        <i class="fas fa-calendar me-1"></i>
                        {{ $cliente->created_at->format('d/m/Y H:i') }}
                    </small>
                </div>

                <!-- Botones de acción -->
                <div class="d-grid gap-2">
                    <button class="btn btn-success" onclick="abrirEnGoogleMaps()">
                        <i class="fas fa-external-link-alt me-1"></i>
                        Abrir en Google Maps
                    </button>
                    <button class="btn btn-info" onclick="compartir()">
                        <i class="fas fa-share me-1"></i>
                        Compartir Ubicación
                    </button>
                    <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Volver al Mapa
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Mapa -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-map me-2"></i>
                    Ubicación de {{ $cliente->nombre }}
                </h5>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 500px; width: 100%;"></div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info me-2"></i>Información Adicional</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6><i class="fas fa-clock me-2"></i>Horarios Sugeridos</h6>
                        <p class="text-muted">
                            @switch($cliente->categoria)
                                @case('cultural')
                                    Martes a Domingo: 9:00 AM - 5:00 PM
                                    @break
                                @case('natural')
                                    Todos los días: 6:00 AM - 6:00 PM
                                    @break
                                @case('historico')
                                    Lunes a Sábado: 8:00 AM - 4:00 PM
                                    @break
                                @case('gastronomico')
                                    Todos los días: 12:00 PM - 10:00 PM
                                    @break
                                @case('aventura')
                                    Fines de semana: 7:00 AM - 5:00 PM
                                    @break
                                @default
                                    Consultar disponibilidad
                            @endswitch
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-dollar-sign me-2"></i>Costo Aproximado</h6>
                        <p class="text-muted">
                            @switch($cliente->categoria)
                                @case('cultural')
                                    S/. 10 - S/. 25
                                    @break
                                @case('natural')
                                    Gratuito - S/. 15
                                    @break
                                @case('historico')
                                    S/. 5 - S/. 20
                                    @break
                                @case('gastronomico')
                                    S/. 30 - S/. 100
                                    @break
                                @case('aventura')
                                    S/. 50 - S/. 200
                                    @break
                                @default
                                    Consultar precios
                            @endswitch
                        </p>
                    </div>
                </div>
                
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6><i class="fas fa-users me-2"></i>Recomendado Para</h6>
                        <p class="text-muted">
                            @switch($cliente->categoria)
                                @case('cultural')
                                    Familias, estudiantes, turistas
                                    @break
                                @case('natural')
                                    Familias, fotógrafos, excursionistas
                                    @break
                                @case('historico')
                                    Estudiantes, investigadores, turistas
                                    @break
                                @case('gastronomico')
                                    Parejas, grupos de amigos
                                    @break
                                @case('aventura')
                                    Deportistas, grupos de aventura
                                    @break
                                @default
                                    Todo público
                            @endswitch
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h6><i class="fas fa-car me-2"></i>Acceso</h6>
                        <p class="text-muted">
                            Transporte público y privado disponible.<br>
                            <a href="#" onclick="calcularRuta()" class="text-primary">
                                <i class="fas fa-route me-1"></i>Calcular ruta desde mi ubicación
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="modalEliminar" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar el punto turístico <strong>"{{ $cliente->nombre }}"</strong>?</p>
                <p class="text-danger"><small>Esta acción no se puede deshacer.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let map;
let marker;

function initMap() {
    const location = { 
        lat: {{ $cliente->latitud }}, 
        lng: {{ $cliente->longitud }} 
    };

    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: location,
        mapTypeId: 'roadmap'
    });

    marker = new google.maps.Marker({
        position: location,
        map: map,
        title: '{{ $cliente->nombre }}',
        icon: {
            url: getIconoCategoria('{{ $cliente->categoria }}'),
            scaledSize: new google.maps.Size(40, 40)
        }
    });

    // InfoWindow con información del punto
    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div style="padding: 10px; max-width: 300px;">
                <h6>{{ $cliente->nombre }}</h6>
                <p><strong>Categoría:</strong> {{ ucfirst($cliente->categoria) }}</p>
                <p>{{ Str::limit($cliente->descripcion, 100) }}</p>
                ${@if($cliente->imagen)'<img src="{{ asset('storage/' . $cliente->imagen) }}" style="width: 100%; max-width: 200px; height: auto; border-radius: 5px;">':''@endif}
            </div>
        `
    });

    marker.addListener('click', () => {
        infoWindow.open(map, marker);
    });

    // Abrir InfoWindow automáticamente
    infoWindow.open(map, marker);
}

function getIconoCategoria(categoria) {
    const iconos = {
        'cultural': 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
        'natural': 'https://maps.google.com/mapfiles/ms/icons/green-dot.png',
        'historico': 'https://maps.google.com/mapfiles/ms/icons/yellow-dot.png',
        'gastronomico': 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
        'aventura': 'https://maps.google.com/mapfiles/ms/icons/purple-dot.png'
    };
    return iconos[categoria] || 'https://maps.google.com/mapfiles/ms/icons/red-dot.png';
}

function abrirEnGoogleMaps() {
    const lat = {{ $cliente->latitud }};
    const lng = {{ $cliente->longitud }};
    const nombre = encodeURIComponent('{{ $cliente->nombre }}');
    const url = `https://www.google.com/maps/search/?api=1&query=${lat},${lng}&query_place_id=${nombre}`;
    window.open(url, '_blank');
}

function copiarCoordenadas() {
    const coordenadas = '{{ $cliente->latitud }}, {{ $cliente->longitud }}';
    navigator.clipboard.writeText(coordenadas).then(function() {
        alert('Coordenadas copiadas al portapapeles: ' + coordenadas);
    }, function(err) {
        console.error('Error al copiar: ', err);
        // Fallback para navegadores más antiguos
        const textArea = document.createElement('textarea');
        textArea.value = coordenadas;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Coordenadas copiadas: ' + coordenadas);
    });
}

function compartir() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $cliente->nombre }} - SIG Turístico',
            text: '{{ $cliente->descripcion }}',
            url: window.location.href
        });
    } else {
        // Fallback para navegadores que no soportan Web Share API
        const url = window.location.href;
        navigator.clipboard.writeText(url).then(function() {
            alert('Enlace copiado al portapapeles');
        });
    }
}

function calcularRuta() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const origen = `${position.coords.latitude},${position.coords.longitude}`;
            const destino = `{{ $cliente->latitud }},{{ $cliente->longitud }}`;
            const url = `https://www.google.com/maps/dir/${origen}/${destino}`;
            window.open(url, '_blank');
        }, function() {
            alert('No se pudo obtener tu ubicación actual.');
        });
    } else {
        alert('Tu navegador no soporta geolocalización.');
    }
}

function eliminarPunto() {
    const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
    modal.show();
}
</script>

<!-- Google Maps API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDkdgSJE1ZFww0OT7TSj-mGZcR9Cl4Hx8M&callback=initMap"></script>
@endsection