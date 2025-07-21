@if (session('success'))
    <x-alert type="success" title="¡Éxito!">
        {{ session('success') }}
    </x-alert>
@endif

@if (session('error'))
    <x-alert type="error" title="¡Error!">
        {{ session('error') }}
    </x-alert>
@endif

@if (session('warning'))
    <x-alert type="warning" title="¡Advertencia!">
        {{ session('warning') }}
    </x-alert>
@endif

@if (session('info'))
    <x-alert type="info" title="Información">
        {{ session('info') }}
    </x-alert>
@endif
