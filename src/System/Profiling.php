<?php

namespace Curatrix\System;

class Profiling {

    public function __construct()
    {

    }

    public static function getInformation()
    {
        return [
            'hostname' => \gethostname(),
            'ip' => gethostbyname(\gethostname()),
            'curatrix_memory_usage' => self::getMemoryUsage(1024*1024),
            'curatrix_memory_usage_peak' => self::getMemoryUsagePeak(1024*1024),
            'memory_usage' => self::getSystemMemInfo(),
            'system_load' => \sys_getloadavg()
        ];
    }

    public static function getInstance()
    {
        return \gethostname();
    }

    public static function getMemoryUsage($precision = 1)
    {
        return (\memory_get_usage() / $precision);
    }

    public static function getMemoryUsagePeak($precision = 1)
    {
        return (\memory_get_peak_usage() / $precision);
    }

    public static function getPidInformation($pid = NULL)
    {
        if(!$pid) return [];
        return ['memory' => self::getMemoryUsage(1024*1024)];
    }

    public static function getSystemMemInfo()
    {
        if(file_exists("/proc/meminfo")) {
            $data = explode("\n", file_get_contents("/proc/meminfo"));
            $meminfo = array();
            foreach ($data as $line) {
                list($key, $val) = explode("\t", $line);
                $meminfo[$key] = trim($val);
            }
            return $meminfo;
        }

        return [];
    }

}