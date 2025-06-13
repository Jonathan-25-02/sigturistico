@extends('layouts.app')

@section('title', 'Mapa Turístico - SIG')

@section('content')
<div class="row">
    <!-- Panel de filtros -->
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-filter me-2"></i>Filtros</h5>
            </div>
            <div class="card-body">
                <!-- Filtro por categoría -->
                <div class="mb-3">
                    <label class="form-label">Categoría:</label>
                    <select class="form-select" id="filtroCategoria">
                        <option value="">Todas las categorías</option>
                        <option value="cultural">Cultural</option>
                        <option value="natural">Natural</option>
                        <option value="historico">Histórico</option>
                        <option value="gastronomico">Gastronómico</option>
                        <option value="aventura">Aventura</option>
                    </select>
                </div>

                <!-- Búsqueda -->
                <div class="mb-3">
                    <label class="form-label">Buscar:</label>
                    <input type="text" class="form-control" id="buscar" placeholder="Nombre o descripción...">
                </div>

                <button type="button" class="btn btn-primary w-100" onclick="filtrarPuntos()">
                    <i class="fas fa-search me-2"></i>Filtrar
                </button>
            </div>
        </div>

        <!-- Lista de puntos -->
        <div class="card mt-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Puntos Turísticos</h5>
                <span class="badge bg-primary" id="contadorPuntos">{{ count($clientes) }}</span>
            </div>
            <div class="card-body p-0" style="max-height: 400px; overflow-y: auto;">
                <div id="listaPuntos">
                    @foreach($clientes as $cliente)
                    <div class="list-group-item list-group-item-action punto-item" 
                         data-id="{{ $cliente->id }}" 
                         data-lat="{{ $cliente->latitud }}" 
                         data-lng="{{ $cliente->longitud }}">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $cliente->nombre }}</h6>
                                <p class="mb-1 text-muted small">{{ Str::limit($cliente->descripcion, 50) }}</p>
                                <span class="categoria-badge {{ $cliente->categoria }}">
                                    {{ ucfirst($cliente->categoria) }}
                                </span>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="{{ route('clientes.show', $cliente) }}">
                                        <i class="fas fa-eye me-2"></i>Ver
                                    </a></li>
                                    <li><a class="dropdown-item" href="{{ route('clientes.edit', $cliente) }}">
                                        <i class="fas fa-edit me-2"></i>Editar
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#" onclick="eliminarPunto({{ $cliente->id }})">
                                        <i class="fas fa-trash me-2"></i>Eliminar
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Mapa -->
    <div class="col-md-9">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-map me-2"></i>Mapa Interactivo</h5>
                <div>
                    <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalAgregar">
                        <i class="fas fa-plus me-1"></i>Agregar Punto
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <div id="map" style="height: 600px; width: 100%;"></div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para agregar punto desde el mapa -->
<div class="modal fade" id="modalAgregar" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Agregar Nuevo Punto Turístico</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formAgregar" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombre *</label>
                                <input type="text" class="form-control" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Categoría *</label>
                                <select class="form-select" name="categoria" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="cultural">Cultural</option>
                                    <option value="natural">Natural</option>
                                    <option value="historico">Histórico</option>
                                    <option value="gastronomico">Gastronómico</option>
                                    <option value="aventura">Aventura</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Descripción *</label>
                        <textarea class="form-control" name="descripcion" rows="3" required></textarea>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Latitud *</label>
                                <input type="number" class="form-control" name="latitud" step="any" required readonly>
                                <small class="text-muted">Haz clic en el mapa para obtener coordenadas</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Longitud *</label>
                                <input type="number" class="form-control" name="longitud" step="any" required readonly>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Imagen</label>
                        <input type="file" class="form-control" name="imagen" accept="image/*">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-1"></i>Guardar Punto
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let map;
let markers = [];
let tempMarker = null;

// Inicializar mapa
function initMap() {
    // Coordenadas por defecto (puedes cambiarlas por tu ciudad)
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 10,
        center: { lat: -12.0464, lng: -77.0428 }, // Lima, Perú
        mapTypeId: 'roadmap'
    });

    // Cargar puntos existentes
    cargarPuntos();

    // Agregar listener para clicks en el mapa
    map.addListener('click', function(e) {
        agregarMarcadorTemporal(e.latLng);
    });
}

// Cargar puntos desde el servidor
function cargarPuntos() {
    fetch('/api/clientes/coordenadas')
        .then(response => response.json())
        .then(data => {
            limpiarMarcadores();
            data.forEach(punto => {
                agregarMarcador(punto);
            });
        })
        .catch(error => console.error('Error:', error));
}

// Agregar marcador al mapa
function agregarMarcador(punto) {
    const marker = new google.maps.Marker({
        position: { lat: parseFloat(punto.latitud), lng: parseFloat(punto.longitud) },
        map: map,
        title: punto.nombre,
        icon: {
            url: getIconoCategoria(punto.categoria),
            scaledSize: new google.maps.Size(30, 30)
        }
    });

    // InfoWindow para mostrar información
    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div style="padding: 10px;">
                <h6>${punto.nombre}</h6>
                <span class="badge bg-primary">${punto.categoria}</span>
                <br><br>
                <button class="btn btn-sm btn-outline-primary" onclick="window.location.href='/clientes/${punto.id}'">
                    Ver detalles
                </button>
            </div>
        `
    });

    marker.addListener('click', () => {
        infoWindow.open(map, marker);
    });

    markers.push(marker);
}

// Obtener icono según categoría
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

// Agregar marcador temporal para nuevo punto
function agregarMarcadorTemporal(location) {
    if (tempMarker) {
        tempMarker.setMap(null);
    }

    tempMarker = new google.maps.Marker({
        position: location,
        map: map,
        draggable: true,
        icon: 'https://maps.google.com/mapfiles/ms/icons/orange-dot.png'
    });

    // Actualizar coordenadas en el formulario
    document.querySelector('input[name="latitud"]').value = location.lat();
    document.querySelector('input[name="longitud"]').value = location.lng();

    // Listener para arrastrar el marcador
    tempMarker.addListener('dragend', function() {
        const pos = tempMarker.getPosition();
        document.querySelector('input[name="latitud"]').value = pos.lat();
        document.querySelector('input[name="longitud"]').value = pos.lng();
    });
}

// Limpiar marcadores
function limpiarMarcadores() {
    markers.forEach(marker => marker.setMap(null));
    markers = [];
}

// Filtrar puntos
function filtrarPuntos() {
    const categoria = document.getElementById('filtroCategoria').value;
    const buscar = document.getElementById('buscar').value;

    const params = new URLSearchParams();
    if (categoria) params.append('categoria', categoria);
    if (buscar) params.append('buscar', buscar);

    fetch(`/clientes?${params.toString()}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        actualizarListaPuntos(data);
        actualizarMapaPuntos(data);
    });
}

// Actualizar lista de puntos
function actualizarListaPuntos(puntos) {
    // Implementar actualización de la lista
    console.log('Actualizando lista con', puntos.length, 'puntos');
}

// Actualizar mapa con puntos filtrados
function actualizarMapaPuntos(puntos) {
    limpiarMarcadores();
    puntos.forEach(punto => {
        agregarMarcador(punto);
    });
}

// Enviar formulario de agregar punto
document.getElementById('formAgregar').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('/clientes', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        bootstrap.Modal.getInstance(document.getElementById('modalAgregar')).hide();
        this.reset();
        if (tempMarker) {
            tempMarker.setMap(null);
            tempMarker = null;
        }
        cargarPuntos();
        location.reload(); // Recargar para ver el nuevo punto en la lista
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al guardar el punto');
    });
});

// Eliminar punto
function eliminarPunto(id) {
    if (confirm('¿Estás seguro de eliminar este punto turístico?')) {
        fetch(`/clientes/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el punto');
        });
    }
}

// Centrar mapa en punto de la lista
document.addEventListener('click', function(e) {
    if (e.target.closest('.punto-item')) {
        const item = e.target.closest('.punto-item');
        const lat = parseFloat(item.dataset.lat);
        const lng = parseFloat(item.dataset.lng);
        
        map.setCenter({ lat: lat, lng: lng });
        map.setZoom(15);
    }
});
</script>

<!-- Google Maps API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDkdgSJE1ZFww0OT7TSj-mGZcR9Cl4Hx8M&callback=initMap"></script>
@endsection