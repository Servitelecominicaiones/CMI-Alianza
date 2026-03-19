@foreach($documentos as $doc)

@php
    $ext = strtolower($doc->extension);
    $preview = $doc->previewUrl();
@endphp

<div class="col-md-3 mb-4">
    <div class="card h-100 shadow-sm">

        {{-- ===== PREVIEW ===== --}}
        <div class="card-img-top bg-light text-center"
             style="height:200px; overflow:hidden">

            {{-- PDF --}}
            @if($ext === 'pdf')
                <iframe src="{{ $preview }}"
                        style="width:100%; height:200px; border:none"></iframe>

            {{-- IMÁGENES --}}
            @elseif(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                <img src="{{ $preview }}"
                     class="img-fluid"
                     style="max-height:200px; object-fit:contain">

            {{-- VIDEO --}}
            @elseif(in_array($ext, ['mp4','webm','ogg']))
                <video controls style="width:100%; height:200px">
                    <source src="{{ $preview }}" type="video/{{ $ext }}">
                </video>

            {{-- OFFICE --}}
            @elseif(in_array($ext, ['doc','docx','xls','xlsx','ppt','pptx']))
                <div class="h-100 d-flex flex-column justify-content-center align-items-center text-muted">
                    <i class="bi bi-file-earmark-text fs-1"></i>
                    <small>{{ strtoupper($ext) }}</small>
                    <small>Sin vista previa</small>
                </div>

            @else
                <div class="h-100 d-flex justify-content-center align-items-center text-muted">
                    <span>Sin preview</span>
                </div>
            @endif
        </div>

        {{-- INFO --}}
        <div class="card-body">
            <h6 class="card-title text-truncate"
                title="{{ $doc->nombre_original }}">
                {{ $doc->nombre_original }}
            </h6>

            <small class="text-muted">
                {{ $doc->categoria->nombre ?? '-' }} ·
                {{ $doc->area->nombre ?? '-' }}
            </small>
        </div>

        {{-- ACCIONES --}}
        <div class="card-footer text-end bg-white">

            {{-- VER --}}
            <a href="{{ $preview }}"
               target="_blank"
               class="btn btn-sm btn-outline-primary">
                <i class="bi bi-eye"></i>
            </a>

            {{-- EDITAR --}}
            @if(in_array('documentos.editar', session('permisos_usuario', [])))
                <a href="{{ route('documentos.edit', $doc->id) }}"
                   class="btn btn-sm btn-outline-warning">
                    <i class="bi bi-pencil"></i>
                </a>
            @endif

            {{-- INACTIVAR --}}
            @if(in_array('documentos.eliminar', session('permisos_usuario', [])))
                <form method="POST" id="formInactivar"
                action="{{ route('documentos.destroy',$doc->id)}}"
                class="d-inline"
                onsubmit="return confirmarInactivacion()">
                @csrf
                @method("PUT")
                    <button type="submit" class="btn btn-sm btn-outline-danger btn-inactivar">
                    <i class="bi bi-trash"></i>
                </form>
            @endif
        </div>

    </div>
</div>

@endforeach

<div class="col-12">
    {{ $documentos->links() }}
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.getElementById('formInactivar').addEventListener('submit', function(e){
            e.preventDefault()

            Swal.fire({
                title: '¿Estas seguro(a) de Inactivar este Documento?',
                text: 'Se inactivara el Documento y no aparecera en el dashboard',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, Inactivar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#0d6efd',
                cancelButtonColor: '#6c757d'
            }).then((result) => {
                if (result.isConfirmed) {

                    // Mostrar loader
                    document.getElementById('loader-overlay').style.display = 'flex';

                    this.submit();
                }
            });
        });
</script>