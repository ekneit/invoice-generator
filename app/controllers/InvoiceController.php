<?php

namespace App\Controllers;

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
        $client = $_POST['client'] ?? null;
        $amount = $_POST['amount'] ?? null;

        // TODO: Refactor this to use a validation library
        if (empty($client) || empty($amount)) {
            http_response_code(400);
            echo 'Client and amount are required';
            exit;
        }

        $this->view('invoice/preview', [
            'title' => 'Store Invoice',
            'client' => htmlspecialchars($client),
            'amount' => number_format((float)$amount, 2)
        ]);
    }
}
