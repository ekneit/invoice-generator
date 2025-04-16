<?php

namespace App\Services;

class GeminiService
{
    public static function extractInvoiceData(string $ocrText): ?array
    {
        $apiKey = $_ENV['GEMINI_API_KEY'];
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key={$apiKey}";

        $prompt = self::buildPrompt($ocrText);

        $body = [
            "contents" => [[
                "parts" => [[
                    "text" => $prompt
                ]]
            ]]
        ];

        $response = self::makeRequest($url, $body);

        return self::extractJsonFromResponse($response);
    }

    private static function buildPrompt(string $text): string
    {
        return <<<PROMPT
                Išanalizuok šį tekstą ir grąžink tik gryną JSON pagal šią struktūrą:
                - Jei imones turi MB “Imones pavadimas”, tai ją reikia įvesti į “seller” name pilna pavadima
                {
                "invoice_number": "SF-123",
                "invoice_date": "2025-04-15",
                "invoice_due": "2025-04-30",
                "seller": {
                    "name": "",
                    "code": "",
                    "vat": "",
                    "address": "",
                    "bank": "",
                    "iban": ""
                },
                "buyer": {
                    "name": "",
                    "code": "",
                    "vat": "",
                    "address": ""
                },
                "vat_rate": 21,
                "items": [
                    {
                    "title": "",
                    "quantity": 1,
                    "unit": "vnt.",
                    "price": 100.00
                    }
                ]
                }

                Analizuok žemiau pateiktą OCR tekstą ir grąžink šios struktūros JSON tik su reikšmėmis:

                $text
                PROMPT;
    }

    private static function makeRequest(string $url, array $body): ?array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_POSTFIELDS => json_encode($body)
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, true);
    }

    private static function extractJsonFromResponse(array $response): ?array
    {
        $text = $response['candidates'][0]['content']['parts'][0]['text'] ?? '';

        $json = json_decode($text, true);

        if (is_array($json)) {
            return $json;
        }

        preg_match('/\{.*\}/s', $text, $matches);
        if (!empty($matches[0])) {
            return json_decode($matches[0], true);
        }

        return null;
    }
}
