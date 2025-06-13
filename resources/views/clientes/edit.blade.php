@extends('layouts.app')

@section('title', 'Editar ' . $cliente->nombre . ' - SIG Turístico')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Editar Punto Turístico
                    </h5>
                    <div>
                        <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye me-1"></i>Ver
                        </a>
                        <a href="{{ route('clientes.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>Volver
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('clientes.update', $cliente) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">
                                    <i class="fas fa-map-marker-alt me-1"></i>Nombre del Lugar *
                                </label>
                                <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                                       id="nombre" name="nombre" value="{{ old('nombre', $cliente->nombre) }}" required>
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
                                    <option value="cultural" {{ old('categoria', $cliente->categoria) == 'cultural' ? 'selected' : '' }}>
                                        🏛️ Cultural
                                    </option>
                                    <option value="natural" {{ old('categoria', $cliente->categoria) == 'natural' ? 'selected' : '' }}>
                                        🌿 Natural
                                    </option>
                                    <option value="historico" {{ old('categoria', $cliente->categoria) == 'historico' ? 'selected' : '' }}>
                                        🏛️ Histórico
                                    </option>
                                    <option value="gastronomico" {{ old('categoria', $cliente->categoria) == 'gastronomico' ? 'selected' : '' }}>
                                        🍽️ Gastronómico
                                    </option>
                                    <option value="aventura" {{ old('categoria', $cliente->categoria) == 'aventura' ? 'selected' : '' }}>
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
                                  placeholder="Describe el lugar turístico..." required>{{ old('descripcion', $cliente->descripcion) }}</textarea>
                        @error('descripcion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="latitud" class="form-label">
                                    <i class="fas fa-crosshairs me-1"></i>Latitud *
                                </label>
                                <input type="number" class="form-control @error('latitud') is-invalid @enderror" 
                                       id="latitud" name="latitud" step="any" 
                                       value="{{ old('latitud', $cliente->latitud) }}" required>
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
                                       id="longitud" name="longitud" step="any" 
                                       value="{{ old('longitud', $cliente->longitud) }}" required>
                                @error('longitud')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Imagen actual y nueva -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="imagen" class="form-label">
                                    <i class="fas fa-image me-1"></i>Nueva Imagen
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
                        </div>
                        
                        <div class="col-md-6">
                            @if($cliente->imagen)
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-image me-1"></i>Imagen Actual
                                    </label>
                                    <div class="border rounded p-2 text-center">
                                        <img src="{{ asset('storage/' . $cliente->imagen) }}" 
                                             alt="{{ $cliente->nombre }}" 
                                             class="img-fluid rounded" 
                                             style="max-height: 150px;">
                                        <div class="mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-file-image me-1"></i>
                                                {{ basename($cliente->imagen) }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="mb-3">
                                    <label class="form-label">
                                        <i class="fas fa-image me-1"></i>Imagen Actual
                                    </label>
                                    <div class="border rounded p-3 text-center text-muted">
                                        <i class="fas fa-image fa-2x mb-2"></i>
                                        <div>Sin imagen</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Botón para obtener coordenadas (opcional) -->
                    <div class="mb-3">
                        <div class="alert alert-info">
                            <i class="fas fa-map-marked-alt me-2"></i>
                            <strong>Tip:</strong> Puedes buscar las coordenadas exactas en 
                            <a href="https://maps.google.com" target="_blank" class="alert-link">Google Maps</a>
                            haciendo clic derecho en la ubicación deseada.
                        </div>
                    </div>

                    <div class="d-flex justify-content-between">
                        <div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Actualizar Punto Turístico
                            </button>
                            <a href="{{ route('clientes.show', $cliente) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancelar
                            </a>
                        </div>
                        
                        <!-- Botón de eliminar (opcional) -->
                        <div>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="fas fa-trash me-1"></i>Eliminar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmación para eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Estás seguro de que deseas eliminar el punto turístico <strong>"{{ $cliente->nombre }}"</strong>?
                <br><br>
                <div class="alert alert-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Esta acción no se puede deshacer.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
                <form action="{{ route('clientes.destroy', $cliente) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>Eliminar Definitivamente
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Preview de imagen antes de subir
    const imageInput = document.getElementById('imagen');
    const currentImageContainer = document.querySelector('.col-md-6:last-child .mb-3');
    
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Crear preview de la nueva imagen
                const previewHtml = `
                    <div class="border rounded p-2 text-center">
                        <img src="${e.target.result}" 
                             alt="Vista previa" 
                             class="img-fluid rounded" 
                             style="max-height: 150px;">
                        <div class="mt-2">
                            <small class="text-success">
                                <i class="fas fa-check me-1"></i>
                                Nueva imagen seleccionada
                            </small>
                        </div>
                    </div>
                `;
                
                // Actualizar el contenedor de imagen actual
                const label = currentImageContainer.querySelector('label');
                label.innerHTML = '<i class="fas fa-image me-1"></i>Vista Previa';
                
                const imageDiv = currentImageContainer.querySelector('div:last-child');
                imageDiv.innerHTML = previewHtml;
            };
            reader.readAsDataURL(file);
        }
    });
});
</script>
@endsection