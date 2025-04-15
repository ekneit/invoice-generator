<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use App\Core\BaseController;

class InvoiceController extends BaseController
{
    public function create()
    {
        $this->view('invoice/create', [
            'title' => 'Create Invoice'
        ]);
    }
    public function store()
    {
        $data = $_POST;

        ob_start();
        include __DIR__ . '/../Views/invoice/pdf_template.php';
        $html = ob_get_clean();

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $invoiceNumber = preg_replace('/[^a-zA-Z0-9-_]/', '_', $data['invoice_number'] ?? 'none');
        $date = $data['invoice_date'] ?? date('Y-m-d');

        $filename = "saskaita_{$invoiceNumber}_{$date}.pdf";
        $dompdf->stream($filename, ['Attachment' => true]);

        exit;
    }
}
