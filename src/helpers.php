<?php

if (! function_exists('filamentWordExportPath')) {
    function filamentWordExportPath(string $path = ''): string
    {
        return __DIR__.'/../'.ltrim($path, '/');
    }
}
