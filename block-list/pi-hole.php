<?php
/**
 * - Read file provided by command line
 * - Transform to pi-hole compatible blocklist file entries
 */

if (empty($argv[1])) {
    fwrite(STDERR, 'Missing file to parse' . PHP_EOL);
    die(1);
}

$files = file($argv[1], FILE_IGNORE_NEW_LINES);

foreach ($files as $i => $file) {
    $data = json_decode(file_get_contents($file));

    if (empty($data)) {
        fwrite(STDERR, 'Invalid file to parse: ' . $file . PHP_EOL);
        die(2);
    }

    fwrite(STDERR, sprintf('%4d/%d %s ... ', $i+1, count($files), str_replace('.json', '', basename($file))));

    // Make sure, each domain only once
    $rules = [];

    foreach ($data->resources as $resource) {
        $rule = strtolower($resource->rule);
        in_array($rule, $rules) || $rules[] = '^' . $rule . '$';
    }

    echo implode(PHP_EOL, $rules), PHP_EOL;

    fwrite(STDERR, count($rules) . PHP_EOL);

    unset($rules);
}
