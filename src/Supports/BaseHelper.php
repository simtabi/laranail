<?php

namespace Simtabi\Laranail\Supports;

use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;

class BaseHelper
{
    private string $configPath = "modules.helpers.config.";

    /**
     * @param Carbon $timestamp
     * @param string $format
     * @return string
     */
    public function formatTime(Carbon $timestamp, string $format = 'j M Y H:i')
    {
        return format_time($timestamp, $format);
    }

    /**
     * @param string|null $date
     * @param string|null $format
     * @return string
     */
    public function formatDate(?string $date, string $format = null)
    {
        if (empty($format)) {
            $format = config($this->configPath.'date_format.date');
        }

        return parseDateFromDatabase($date, $format);
    }

    /**
     * @param int $bytes
     * @param int $precision
     * @return string
     */
    public function humanFilesize(int $bytes, int $precision = 2)
    {
        return humanFileSize($bytes, $precision);
    }

    /**
     * @param string $file
     * @param bool $convertToArray
     * @return array|bool|mixed|null
     * @throws \JsonException
     */
    public function getFileData(string $file, bool $convertToArray = true)
    {
        return getFileData($file, $convertToArray);
    }

    /**
     * @param string $path
     * @param string $data
     * @param bool $json
     * @return bool|mixed
     */
    public function saveFileData(string $path, string $data, bool $json)
    {
        return saveFileData($path, $data, $json);
    }

    /**
     * @param array $data
     * @return string
     * @throws \JsonException
     */
    public function jsonEncodePrettify(array $data)
    {
        return json_encode_prettify($data);
    }

    /**
     * @param string $path
     * @param array $ignoreFiles
     * @return array
     */
    public function scanFolder(string $path, array $ignoreFiles = [])
    {
        return scanFolder($path, $ignoreFiles);
    }

    /**
     * @return Repository|Application|mixed
     */
    public function getAdminPrefix(): string
    {
        return config($this->configPath.'admin_dir');
    }

    /**
     * @return string
     */
    public function siteLanguageDirection()
    {
        return config($this->configPath.'locale_direction', 'ltr');
    }

}
