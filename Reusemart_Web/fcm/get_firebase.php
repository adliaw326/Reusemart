<?php

require 'vendor/autoload.php';

use Google\Auth\Credentials\ServiceAccountCredentials;

$scopes = ['https://www.googleapis.com/auth/firebase.messaging'];
$credentialsPath = __DIR__ . '/firebase-service-account.json';


$creds = new ServiceAccountCredentials($scopes, $credentialsPath);

// Ambil token
$token = $creds->fetchAuthToken();

echo "Access Token:\n";
echo $token['access_token'] ?? 'Gagal ambil token';
