<?php

error_log('Request received!' . PHP_EOL);
if ('post' === strtolower($_SERVER['REQUEST_METHOD'])) {
    $remotePath = $_POST['path'];
    error_log('Downloading ' . $remotePath . PHP_EOL);
    if (!($remoteServer = ftp_connect(getenv('FTP_HOST')))) {
        http_response_code(500);
        trigger_error('Not connected :(' . PHP_EOL);
        die;
    }
    trigger_error('Connected!' . PHP_EOL);
    if (!ftp_login($remoteServer, getenv('FTP_USER'), getenv('FTP_PWD'))) {
        http_response_code(500);
        trigger_error('Wrong credentials' . PHP_EOL);
        die;
    }
    trigger_error('Login succesful!' . PHP_EOL);
    if (!ftp_get($remoteServer, __DIR__ . '/' . basename($remotePath), $remotePath)) {
        trigger_error('Download of file '.$remotePath.' failed' . PHP_EOL);
        http_response_code(500);
        die;
    }
    trigger_error('Sucessuflly downloaded '.$remotePath.PHP_EOL);
}