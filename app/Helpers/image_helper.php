<?php

if (!function_exists('removeBackground')) {
    function removeBackground(string $filePath, string $type = 'main'): string|false
    {
        $apiKey = getenv('REMOVE_BG_API_KEY');
        $outputDir = '';

        if ($type === 'main') {
            $outputDir = FCPATH . 'img/uploads/main/';
        }

        if ($type === 'adds') {
            $outputDir = FCPATH . 'img/uploads/adds/';
        }

        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.remove.bg/v1.0/removebg');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "X-Api-Key: $apiKey",
        ]);

        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'image_file' => new CURLFile($filePath),
            'size' => 'auto',
        ]);

        $response = curl_exec($ch);
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatus != 200) {
            log_message('error', 'remove.bg failed: ' . $response);
            return false;
        }

        $fileName = uniqid('no-bg-', true) . '.png';
        $fullpath = $outputDir . $fileName;
        file_put_contents($fullpath, $response);

        return $fileName;
    }
}
