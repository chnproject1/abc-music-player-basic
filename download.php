<?php
// ──────────────────────────────────────────
//  abcMusic — Proxy de download de áudio
//  Força o download do MP3 (cross-origin)
// ──────────────────────────────────────────

$url      = $_GET['url']      ?? '';
$filename = $_GET['filename'] ?? 'minha-musica.mp3';

// Aceita apenas URLs do Supabase desta conta
if (!$url || !preg_match('/^https:\/\/baltzukuszagxcgkfrpi\.supabase\.co\/storage\//', $url)) {
    http_response_code(400);
    exit('URL inválida');
}

// Sanitiza o nome do arquivo
$filename = preg_replace('/[^\w\-. áàâãéèêíïóôõöúüçÁÀÂÃÉÈÊÍÏÓÔÕÖÚÜÇ]/u', '_', $filename);
if (!preg_match('/\.mp3$/i', $filename)) {
    $filename .= '.mp3';
}

// Busca o arquivo no Supabase
$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_USERAGENT      => 'abcMusic/1.0',
    CURLOPT_TIMEOUT        => 30,
]);
$data     = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($data === false || $httpCode !== 200) {
    http_response_code(502);
    exit('Erro ao buscar arquivo');
}

header('Content-Type: audio/mpeg');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Length: ' . strlen($data));
header('Cache-Control: no-store');
echo $data;
