@php
    $cacheKey = $cacheKey ?? 'default_cache_key';
    $ttl = $ttl ?? 3600; // 1 hora por defecto
@endphp

@cache($cacheKey, $ttl)
    {{ $slot }}
@endcache
