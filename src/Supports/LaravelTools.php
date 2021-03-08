<?php

namespace Simtabi\Laranail\Supports;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Artisan;
use Cache;
use Eloquent;
use Event;
use Exception;
use File;
use Request;
use Schema;
use App;

class LaravelTools
{

    /**
     * Load helpers from a directory
     * @param string $directory
     * @since 2.0
     */
    public static function autoload(string $directory): void
    {
        $helpers = File::glob($directory . '/*.php');
        foreach ($helpers as $helper) {
            File::requireOnce($helper);
        }
    }

    /**
     * @param Eloquent | Model $object
     * @param string $sessionName
     * @return bool
     */
    public static function handleViewCount(Eloquent $object, string $sessionName): bool
    {
        if (!array_key_exists($object->id, session()->get($sessionName, []))) {
            try {
                $object->newQuery()->increment('views');
                session()->put($sessionName . '.' . $object->id, time());
                return true;
            } catch (Exception $exception) {
                return false;
            }
        }

        return false;
    }

    /**
     * @return bool
     */
    public static function isConnectedDatabase(): bool
    {
        try {
            return Schema::hasTable('settings');
        } catch (Exception $exception) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public static function clearCache(): bool
    {
        Event::dispatch('cache:clearing');

        try {
            Cache::flush();
            if (!File::exists($storagePath = storage_path('framework/cache'))) {
                return true;
            }

            foreach (File::files($storagePath) as $file) {
                if (preg_match('/facade-.*\.php$/', $file)) {
                    File::delete($file);
                }
            }
        } catch (Exception $exception) {
            info($exception->getMessage());
        }

        Event::dispatch('cache:cleared');

        return true;
    }



    /**
     * Get the Composer file contents as an array
     * @return array
     * @throws FileNotFoundException
     */
    public static function getComposerArray()
    {
        return getFileData(base_path('composer.json'));
    }

    /**
     * Get Installed packages & their Dependencies
     *
     * @param array $packagesArray
     * @return array
     */
    public static function getPackagesAndDependencies(array $packagesArray): array
    {
        $packages = [];
        foreach ($packagesArray as $key => $value) {
            $packageFile = base_path('vendor/' . $key . '/composer.json');

            if ($key !== 'php' && File::exists($packageFile)) {
                $json2             = file_get_contents($packageFile);
                $dependenciesArray = json_decode($json2, true);
                $dependencies      = array_key_exists('require', $dependenciesArray) ?
                    $dependenciesArray['require'] : 'No dependencies';
                $devDependencies = array_key_exists('require-dev', $dependenciesArray) ?
                    $dependenciesArray['require-dev'] : 'No dependencies';

                $packages[] = [
                    'name'             => $key,
                    'version'          => $value,
                    'dependencies'     => $dependencies,
                    'dev-dependencies' => $devDependencies,
                ];
            }
        }

        return $packages;
    }

    /**
     * Get System environment details
     *
     * @return array
     */
    public static function getSystemEnv(): array
    {
        return [
            'version'              => App::version(),
            'timezone'             => config('app.timezone'),
            'debug_mode'           => config('app.debug'),
            'storage_dir_writable' => File::isWritable(base_path('storage')),
            'cache_dir_writable'   => File::isReadable(base_path('bootstrap/cache')),
            'app_size'             => humanFileSize(self::folderSize(base_path())),
        ];
    }

    /**
     * Get the system app's size
     *
     * @param string $directory
     * @return int
     */
    protected static function folderSize($directory): int
    {
        $size = 0;
        foreach (File::glob(rtrim($directory, '/') . '/*', GLOB_NOSORT) as $each) {
            $size += File::isFile($each) ? File::size($each) : self::folderSize($each);
        }

        return $size;
    }

    /**
     * Check if SSL is installed or not
     * @return boolean
     */
    protected static function isSslIsInstalled(): bool
    {
        return !empty(Request::server('HTTPS')) && Request::server('HTTPS') !== 'off';
    }

    /**
     * Get PHP/Server environment details
     * @return array
     */
    public static function getServerEnv(): array
    {
        return [
            'version'                  => phpversion(),
            'server_software'          => Request::server('SERVER_SOFTWARE'),
            'server_os'                => function_exists('php_uname') ? php_uname() : 'N/A',
            'database_connection_name' => config('database.default'),
            'ssl_installed'            => self::isSslIsInstalled(),
            'cache_driver'             => config('cache.default'),
            'session_driver'           => config('session.driver'),
            'queue_connection'         => config('queue.default'),
            'mbstring'                 => extension_loaded('mbstring'),
            'openssl'                  => extension_loaded('openssl'),
            'curl'                     => extension_loaded('curl'),
            'exif'                     => extension_loaded('exif'),
            'pdo'                      => extension_loaded('pdo'),
            'fileinfo'                 => extension_loaded('fileinfo'),
            'tokenizer'                => extension_loaded('tokenizer'),
        ];
    }

}
