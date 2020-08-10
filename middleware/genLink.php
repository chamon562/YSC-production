<?php 
// Allow Cross Origin Requests (other ips can request data)
header("Access-Control-Allow-Origin: *");
// Load the JWT library
require_once __DIR__ . '/vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

$jwt = htmlspecialchars_decode($_GET["jwt"]);
$filename = htmlspecialchars_decode($_GET["vid"]);

$credentials = json_decode(include "verifyNoEcho.php");

if($credentials == "Error: 405. This key has been tampered with or is out of date." || $credentials == "Error: 406. Please Provide a JSON Web Token.") {
    echo $credentials;
    return $credentials;
}

$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region' => 'us-east-2',
    'credentials' => [
        'key'    => 'AKIA3W5HAAMI6L45OV5X',
        'secret' => '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'
    ]
]);

$cmd = $s3->getCommand('GetObject', [
    'Bucket' => 'ystemandchess-meeting-recordings',
    'Key' => $filename
]);

$request = $s3->createPresignedRequest($cmd, '+20 minutes');
$presignedUrl = (string)$request->getUri();
echo ($presignedUrl)
?>