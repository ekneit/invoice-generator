<?php

namespace App\Controllers;


use Smalot\PdfParser\Parser;
use App\Core\BaseController;
use App\Services\GeminiService;
use thiagoalessio\TesseractOCR\TesseractOCR;

class UploadController extends BaseController
{
    public function showForm()
    {
        $this->view('invoice/upload', [
            'title' => 'Įkelti PDF / nuotrauką'
        ]);
    }

    public function handleUpload()
    {
        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo 'Nepavyko įkelti failo';
            exit;
        }

        $tmpPath = $_FILES['file']['tmp_name'];
        $mime = mime_content_type($tmpPath);

        if (!in_array($mime, ['application/pdf', 'image/jpeg', 'image/png'])) {
            http_response_code(400);
            echo 'Leidžiami tik PDF, JPG, PNG';
            exit;
        }

        if ($mime === 'application/pdf') {
            $parser = new Parser();
            $pdf = $parser->parseFile($tmpPath);
            $ocrText = $pdf->getText();
        } else {
            $ocrText = (new TesseractOCR($tmpPath))->run();
        }


        if (empty($ocrText)) {
            http_response_code(400);
            echo 'Nepavyko išgauti teksto';
            exit;
        }

        $data = GeminiService::extractInvoiceData($ocrText);


        if (!$data) {
            http_response_code(500);
            echo 'Gemini AI nesugeneravo tinkamo JSON';
            exit;
        }

        $invoice = [
            'invoice_number' => $data['invoice_number'] ?? '',
            'invoice_date' => $data['invoice_date'] ?? '',
            'invoice_due' => $data['invoice_due'] ?? '',
            'seller_name' => $data['seller']['name'] ?? '',
            'seller_code' => $data['seller']['code'] ?? '',
            'seller_vat' => $data['seller']['vat'] ?? '',
            'seller_address' => $data['seller']['address'] ?? '',
            'seller_bank' => $data['seller']['bank'] ?? '',
            'seller_iban' => $data['seller']['iban'] ?? '',
            'buyer_name' => $data['buyer']['name'] ?? '',
            'buyer_code' => $data['buyer']['code'] ?? '',
            'buyer_vat' => $data['buyer']['vat'] ?? '',
            'buyer_address' => $data['buyer']['address'] ?? '',
            'vat_rate' => $data['vat_rate'] ?? 21,
        ];

        $items = $data['items'] ?? [];

        $this->view('invoice/create', [
            'title' => 'Automatinė sąskaita',
            'invoice' => $invoice,
            'items' => $items,
        ]);
    }
}
