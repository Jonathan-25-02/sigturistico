@extends('layouts.app')

@section('title', 'Agregar Punto Turístico - SIG')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4><i class="fas fa-plus-circle me-2"></i>Agregar Nuevo Punto Turístico</h4>
            </div>
            <div class="card-body">
                <!-- Mostrar errores de sesión -->
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Mostrar errores de validación -->
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Por favor corrige los siguientes errores:</strong>
                        <ul class="mb-0 mt-2">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form id="puntoTuristicoForm" action="{{ route('clientes.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-map-marker-alt me-1"></i>Nombre del Lugar *
                                </label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                                @error('nombre')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="categoria" class="form-label">
                                    <i class="fas fa-tags me-1"></i>Categoría *
                                </label>
                                <select class="form-select @error('categoria') is-invalid @enderror" 
                                        id="categoria" name="categoria" required>
                                    <option value="">Seleccionar categoría...</option>
                                    <option value="cultural" {{ old('categoria') == 'cultural' ? 'selected' : '' }}>
                                        🏛️ Cultural
                                    </option>
                                    <option value="natural" {{ old('categoria') == 'natural' ? 'selected' : '' }}>
                                        🌿 Natural
                                    </option>
                                    <option value="historico" {{ old('categoria') == 'historico' ? 'selected' : '' }}>
                                        🏛️ Histórico
                                    </option>
                                    <option value="gastronomico" {{ old('categoria') == 'gastronomico' ? 'selected' : '' }}>
                                        🍽️ Gastronómico
                                    </option>
                                    <option value="aventura" {{ old('categoria') == 'aventura' ? 'selected' : '' }}>
                                        🏔️ Aventura
                                    </option>
                                </select>
                                @error('categoria')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="descripcion" class="form-label">
                            <i class="fas fa-align-left me-1"></i>Descripción *
                        </label>
                        <textarea class="form-control @error('descripcion') is-invalid @enderror" 
                                  id="descripcion" name="descripcion" rows="4" 
                                  placeholder="Describe el lugar turístico..." required>{{ old('descripcion') }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="imagen" class="form-label">
                            <i class="fas fa-image me-1"></i>Imagen
                        </label>
                        <input type="file" class="form-control @error('imagen') is-invalid @enderror" 
                               id="imagen" name="imagen" accept="image/*">
                        <div class="form-text">
                            <i class="fas fa-info-circle me-1"></i>
                            Formatos permitidos: JPG, PNG, GIF. Máximo 2MB.
                        </div>
                        @error('imagen')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Sección del mapa para seleccionar coordenadas -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-map-marked-alt me-1"></i>Ubicación en el Mapa *
                        </label>
                        <div class="card">
                            <div class="card-body">
                                <div class="alert alert-info mb-3">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Instrucciones:</strong>
                                    <ul class="mb-0 mt-2">
                                        <li>Haz clic en el mapa para seleccionar la ubicación exacta</li>
                                        <li>Puedes arrastrar el marcador para ajustar la posición</li>
                                        <li>Usa el botón azul para obtener tu ubicación actual</li>
                                    </ul>
                                </div>
                                <div id="map" style="height: 400px; width: 100%; border-radius: 8px;"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitud" class="form-label">
                                    <i class="fas fa-crosshairs me-1"></i>Latitud *
                                </label>
                                <input type="number" class="form-control @error('latitud') is-invalid @enderror" 
                                       id="latitud" name="latitud" step="any" value="{{ old('latitud') }}" required readonly>
                                @error('latitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="longitud" class="form-label">
                                    <i class="fas fa-crosshairs me-1"></i>Longitud *
                                </label>
                                <input type="number" class="form-control @error('longitud') is-invalid @enderror" 
                                       id="longitud" name="longitud" step="any" value="{{ old('longitud') }}" required readonly>
                                @error('longitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Validación de coordenadas -->
                    <div id="coordenadasError" class="alert alert-warning" style="display: none;">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Por favor selecciona una ubicación en el mapa antes de guardar.
                    </div>
                    
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-1"></i>Volver al Mapa
                        </a>
                        <button type="submit" class="btn btn-primary" id="btnGuardar">
                            <i class="fas fa-save me-1"></i>Guardar Punto Turístico
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Botón para obtener ubicación actual -->
<div class="position-fixed" style="bottom: 20px; right: 20px; z-index: 1000;">
    <button type="button" class="btn btn-info btn-lg rounded-circle shadow" onclick="obtenerUbicacionActual()" title="Obtener mi ubicación">
        <i class="fas fa-crosshairs"></i>
    </button>
</div>
@endsection

@section('scripts')
<script>
let map;
let marker = null;

function initMap() {
    // Coordenadas por defecto (Lima, Perú - puedes cambiarlas)
    const defaultLocation = { lat: -12.0464, lng: -77.0428 };
    
    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 12,
        center: defaultLocation,
        mapTypeId: 'roadmap',
        styles: [
            {
                featureType: 'poi',
                elementType: 'labels',
                stylers: [{ visibility: 'on' }]
            }
        ]
    });

    // Si hay coordenadas anteriores (old input), mostrar marcador
    const latitud = document.getElementById('latitud').value;
    const longitud = document.getElementById('longitud').value;
    
    if (latitud && longitud) {
        const position = { lat: parseFloat(latitud), lng: parseFloat(longitud) };
        agregarMarcador(position);
        map.setCenter(position);
    }

    // Listener para clicks en el mapa
    map.addListener('click', function(e) {
        agregarMarcador(e.latLng);
        actualizarCoordenadas(e.latLng);
    });
}

function agregarMarcador(location) {
    // Remover marcador anterior si existe
    if (marker) {
        marker.setMap(null);
    }

    // Crear nuevo marcador
    marker = new google.maps.Marker({
        position: location,
        map: map,
        draggable: true,
        title: 'Ubicación del punto turístico',
        icon: {
            url: 'https://maps.google.com/mapfiles/ms/icons/red-dot.png',
            scaledSize: new google.maps.Size(32, 32)
        },
        animation: google.maps.Animation.DROP
    });

    // Listener para cuando se arrastra el marcador
    marker.addListener('dragend', function() {
        actualizarCoordenadas(marker.getPosition());
    });

    // Ocultar mensaje de error si estaba visible
    document.getElementById('coordenadasError').style.display = 'none';
}

function actualizarCoordenadas(location) {
    const lat = location.lat();
    const lng = location.lng();
    
    document.getElementById('latitud').value = lat.toFixed(8);
    document.getElementById('longitud').value = lng.toFixed(8);
    
    console.log('Coordenadas actualizadas:', { lat, lng });
}

// Obtener ubicación actual del usuario
function obtenerUbicacionActual() {
    const btn = document.querySelector('.btn-info');
    const originalContent = btn.innerHTML;
    
    // Mostrar loading
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    btn.disabled = true;
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const userLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            
            map.setCenter(userLocation);
            map.setZoom(15);
            agregarMarcador(userLocation);
            actualizarCoordenadas(userLocation);
            
            // Restaurar botón
            btn.innerHTML = originalContent;
            btn.disabled = false;
            
            // Mostrar notificación
            mostrarNotificacion('Ubicación obtenida exitosamente', 'success');
            
        }, function(error) {
            // Restaurar botón
            btn.innerHTML = originalContent;
            btn.disabled = false;
            
            let mensaje = 'No se pudo obtener tu ubicación actual.';
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    mensaje = 'Permisos de ubicación denegados.';
                    break;
                case error.POSITION_UNAVAILABLE:
                    mensaje = 'Ubicación no disponible.';
                    break;
                case error.TIMEOUT:
                    mensaje = 'Tiempo de espera agotado.';
                    break;
            }
            
            mostrarNotificacion(mensaje, 'error');
        });
    } else {
        btn.innerHTML = originalContent;
        btn.disabled = false;
        mostrarNotificacion('Tu navegador no soporta geolocalización.', 'error');
    }
}

// Función para mostrar notificaciones
function mostrarNotificacion(mensaje, tipo) {
    const alertClass = tipo === 'success' ? 'alert-success' : 'alert-danger';
    const iconClass = tipo === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle';
    
    const notificacion = document.createElement('div');
    notificacion.className = `alert ${alertClass} alert-dismissible fade show position-fixed`;
    notificacion.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notificacion.innerHTML = `
        <i class="fas ${iconClass} me-2"></i>
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notificacion);
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (notificacion && notificacion.parentNode) {
            notificacion.remove();
        }
    }, 5000);
}

// Validación antes de enviar el formulario
document.getElementById('puntoTuristicoForm').addEventListener('submit', function(e) {
    const latitud = document.getElementById('latitud').value;
    const longitud = document.getElementById('longitud').value;
    
    if (!latitud || !longitud) {
        e.preventDefault();
        document.getElementById('coordenadasError').style.display = 'block';
        document.getElementById('coordenadasError').scrollIntoView({ behavior: 'smooth' });
        return false;
    }
    
    // Mostrar loading en el botón
    const btnGuardar = document.getElementById('btnGuardar');
    btnGuardar.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>Guardando...';
    btnGuardar.disabled = true;
});

// Manejar errores de carga del mapa
window.gm_authFailure = function() {
    document.getElementById('map').innerHTML = `
        <div class="alert alert-danger text-center h-100 d-flex align-items-center justify-content-center">
            <div>
                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                <br>
                Error al cargar Google Maps. Verifica la API Key.
            </div>
        </div>
    `;
};
</script>

<!-- Google Maps API -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDkdgSJE1ZFww0OT7TSj-mGZcR9Cl4Hx8M&callback=initMap"></script>
@endsection