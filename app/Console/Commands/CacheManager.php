<?php

namespace App\Console\Commands;

use App\Helpers\CacheHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class CacheManager extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:manage 
                            {action : Acción a realizar (clear, warm, status)}
                            {--type=all : Tipo de cache a gestionar (config, stats, encuestas, all)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gestiona el cache del sistema de encuestas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $action = $this->argument('action');
        $type = $this->option('type');

        switch ($action) {
            case 'clear':
                $this->clearCache($type);
                break;
            case 'warm':
                $this->warmCache($type);
                break;
            case 'status':
                $this->showStatus();
                break;
            default:
                $this->error('Acción no válida. Use: clear, warm, status');
                return 1;
        }

        return 0;
    }

    /**
     * Limpia el cache especificado
     *
     * @param string $type
     * @return void
     */
    private function clearCache(string $type): void
    {
        $this->info('Limpiando cache...');

        switch ($type) {
            case 'config':
                CacheHelper::limpiarCacheConfiguraciones();
                $this->info('✅ Cache de configuraciones limpiado');
                break;
            case 'stats':
                CacheHelper::limpiarCacheEstadisticas();
                $this->info('✅ Cache de estadísticas limpiado');
                break;
            case 'encuestas':
                CacheHelper::limpiarCacheEncuestas();
                $this->info('✅ Cache de encuestas limpiado');
                break;
            case 'all':
                CacheHelper::limpiarTodoCache();
                $this->info('✅ Todo el cache limpiado');
                break;
            default:
                $this->error('Tipo de cache no válido');
                break;
        }
    }

    /**
     * Precalienta el cache especificado
     *
     * @param string $type
     * @return void
     */
    private function warmCache(string $type): void
    {
        $this->info('Precalentando cache...');

        switch ($type) {
            case 'config':
                $this->warmConfigCache();
                break;
            case 'stats':
                $this->warmStatsCache();
                break;
            case 'encuestas':
                $this->warmEncuestasCache();
                break;
            case 'all':
                $this->warmConfigCache();
                $this->warmStatsCache();
                $this->warmEncuestasCache();
                break;
            default:
                $this->error('Tipo de cache no válido');
                break;
        }
    }

    /**
     * Precalienta el cache de configuraciones
     *
     * @return void
     */
    private function warmConfigCache(): void
    {
        $this->line('Precalentando cache de configuraciones...');

        CacheHelper::getTiposPregunta();
        CacheHelper::getParametrosActivos();

        $this->info('✅ Cache de configuraciones precalentado');
    }

    /**
     * Precalentamiento del cache de estadísticas
     *
     * @return void
     */
    private function warmStatsCache(): void
    {
        $this->line('Precalentando cache de estadísticas...');

        CacheHelper::getEstadisticasSistema();

        $this->info('✅ Cache de estadísticas precalentado');
    }

    /**
     * Precalentamiento del cache de encuestas
     *
     * @return void
     */
    private function warmEncuestasCache(): void
    {
        $this->line('Precalentando cache de encuestas...');

        // Precalentar lista de personas
        Cache::remember('personas_list', 3600, function () {
            return \App\Models\Persona::orderBy('primer_nombre')->get();
        });

        $this->info('✅ Cache de encuestas precalentado');
    }

    /**
     * Muestra el estado del cache
     *
     * @return void
     */
    private function showStatus(): void
    {
        $this->info('Estado del Cache del Sistema');
        $this->line('============================');

        $cacheKeys = [
            'dashboard_stats' => 'Estadísticas del Dashboard',
            'personas_list' => 'Lista de Personas',
            'tipos_pregunta' => 'Tipos de Pregunta',
            'parametros_activos' => 'Parámetros Activos',
            'estadisticas_sistema' => 'Estadísticas del Sistema',
            'encuestas_activas' => 'Encuestas Activas',
            'total_encuestas' => 'Total de Encuestas',
        ];

        foreach ($cacheKeys as $key => $description) {
            $status = Cache::has($key) ? '✅ Cached' : '❌ No cached';
            $this->line("{$description}: {$status}");
        }

        $this->line('');
        $this->info('Comandos disponibles:');
        $this->line('  php artisan cache:manage clear --type=all');
        $this->line('  php artisan cache:manage warm --type=all');
        $this->line('  php artisan cache:manage status');
    }
}
