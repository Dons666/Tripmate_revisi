<?php
$ch = curl_init('http://localhost:8000/api/integrated-route');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Accept: application/json'));
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'start' => 'dago',
    'end' => 'braga',
    'budget' => 100000,
    'jumlah_orang' => 1
]));
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
echo "HTTP Code: $httpcode\n";
echo "Response Error Message: " . substr($response, 0, 1000) . "\n";
