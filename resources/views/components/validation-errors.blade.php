@props(['errors'])

@if ($errors->any())
    <x-alert type="error" title="Errores de validación">
        <div class="row">
            <div class="col-12">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </x-alert>
@endif
