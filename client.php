<?php

if (!($remoteServer = ftp_connect(getenv('FTP_HOST')))) {
    die('Not connected :(' . PHP_EOL);
}
echo 'Connected!' . PHP_EOL;
if (!ftp_login($remoteServer, getenv('FTP_USER'), getenv('FTP_PWD'))) {
    die('Wrong credentials' . PHP_EOL);
}
echo 'Login succesful!' . PHP_EOL;
$remoteFullPath = getenv('REMOTE_BASE_PATH') . '/' . basename($argv[1]);
if (!ftp_put($remoteServer, $remoteFullPath, $argv[1], FTP_BINARY)) {
    die('Upload failed');
}

echo 'File ' . $argv[1] . ' uploaded!'.PHP_EOL;

ftp_close($remoteServer);

$ch = curl_init(getenv('SERVER_URL') . '/upload');
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => [
        'path' => $remoteFullPath
    ],
    CURLOPT_RETURNTRANSFER => true,
]);

$response = curl_exec($ch);
if (200 !== curl_getinfo($ch, CURLINFO_RESPONSE_CODE)) {
    die('Webservice call failed: ' . curl_error($ch));
}
curl_close($ch);
echo 'Message sent!' . PHP_EOL;