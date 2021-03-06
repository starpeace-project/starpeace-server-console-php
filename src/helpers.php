<?php

if (!function_exists('check_dir')) {
    /**
     * @param string $path
     * @param bool $create
     * @return bool
     */
    function check_dir(string $path, bool $create = true): bool
    {
        if (!is_dir($path)) {
            return $create ? @mkdir($path) : false;
        }

        return true;
    }
}

if (!function_exists('copy_file')) {
    /**
     * @param string $fromDir
     * @param string $toDir
     * @param string $file
     */
    function copy_file(string $fromDir, string $toDir, string $file)
    {
        @copy(path_join($fromDir, $file), path_join($toDir, $file));
    }
}

if (!function_exists('copy_files')) {
    /**
     * @param string $fromDir
     * @param string $toDir
     * @param array $files
     */
    function copy_files(string $fromDir, string $toDir, array $files)
    {
        array_walk($files, function ($file) use ($fromDir, $toDir) {
            copy_file($fromDir, $toDir, $file);
        });
    }
}

if (!function_exists('define_testing')) {
    /**
     * @param bool $testing
     * @param array $extraDefines
     */
    function define_testing(bool $testing, array $extraDefines = [])
    {
        $extraDefines = ['TESTING' => $testing] + $extraDefines;

        foreach ($extraDefines as $define => $value) {
            define(strtoupper((string)$define), $value);
        }
    }
}

if (!function_exists('strncmp_get_files')) {
    /**
     * @param string $path
     * @param string $needle
     * @return array
     */
    function strncmp_get_files(string $path, string $needle): array
    {
        $files = scandir($path);
        $files = array_filter($files, function ($file) use ($needle) {
            return strncmp($file, $needle, strlen($needle)) === 0;
        });

        return array_values($files);
    }
}

if (!function_exists('file_lines')) {
    /**
     * @param string $path
     * @param string $file
     * @param bool $useFileName
     * @return array
     */
    function file_lines(string $path, string $file, bool $useFileName = true): array
    {
        $lines = [];
        foreach (file(path_join($path, $file)) as $line) {
            $lines[$useFileName ? $file : null][] = trim(preg_replace("/\s+/", " ", $line));
        }

        return $lines;
    }
}

if (!function_exists('file_lines_multi')) {
    /**
     * @param string $path
     * @param array $files
     * @param bool $useFileName
     * @return array
     */
    function file_lines_multi(string $path, array $files, bool $useFileName = true): array
    {
        $lines = [];
        foreach ($files as $file) {
            $lines = array_merge($lines, file_lines($path, $file, $useFileName));
        }

        return $lines;
    }
}

if (!function_exists('path_join')) {
    /**
     * @return string
     */
    function path_join(): string
    {
        return implode(DIRECTORY_SEPARATOR, array_map('strval', func_get_args()));
    }
}

if (!function_exists('cli')) {
    /**
     * @return bool
     */
    function cli(): bool
    {
        return php_sapi_name() === 'cli';
    }
}

if (!function_exists('array_multi_group_by_key')) {
    function array_multi_group_by_key(array &$data, string $key, bool $return = false): array {
        $output = [];
        foreach ($data as $array) {
            if (!empty($array[$key])) {
                $output[$array[$key]][] = $array;
            }
        }

        if (!$return) {
            $data = $output;
        }

        return $output;
    }
}

if (!function_exists('array_keys_multi')) {
    function array_keys_multi(array $array): array {
        $output = [];
        foreach ($array as $newArray) {
            $output = array_merge($output, $newArray);
        }

        return $output;
    }
}

if (!function_exists('array_unique_values')) {
    function array_unique_values(array $array): array {
        return array_unique(array_values($array));
    }
}

if (!function_exists('array_default_multi')) {
    function array_default(array &$array, $default = []) {
        foreach ($array as $key => &$innerArray) {
            $innerArray = $default;
        }
    }
}

