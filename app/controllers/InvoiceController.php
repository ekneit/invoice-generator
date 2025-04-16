<?php

namespace App\Controllers;

use Dompdf\Dompdf;
use App\Core\Auth;
use App\Core\Database;
use App\Core\BaseController;

// TODO: Need to add error handling

class InvoiceController extends BaseController
{

    public function update($id)
    {
        $pdo = Database::connect();
        $pdo->beginTransaction();

        try {
            $data = $_POST;

            $stmt = $pdo->prepare("
            UPDATE invoices SET
                invoice_number = ?, invoice_date = ?, invoice_due = ?,
                seller_name = ?, seller_code = ?, seller_vat = ?, seller_address = ?, seller_bank = ?, seller_iban = ?,
                buyer_name = ?, buyer_code = ?, buyer_vat = ?, buyer_address = ?,
                vat_rate = ?, subtotal = ?, vat_amount = ?, total = ?
            WHERE id = ? AND user_id = ?
        ");

            $stmt->execute([
                $data['invoice_number'],
                $data['invoice_date'],
                $data['invoice_due'] ?? null,

                $data['seller_name'],
                $data['seller_code'],
                $data['seller_vat'],
                $data['seller_address'],
                $data['seller_bank'],
                $data['seller_iban'],

                $data['buyer_name'],
                $data['buyer_code'],
                $data['buyer_vat'],
                $data['buyer_address'],

                $data['vat_rate'] ?? 21,
                (float) $data['subtotal'],
                (float) $data['vat_amount'],
                (float) $data['total'],

                $id,
                Auth::id()
            ]);

            $stmt = $pdo->prepare("DELETE FROM invoice_items WHERE invoice_id = ?");
            $stmt->execute([$id]);

            foreach ($data['item_title'] as $i => $title) {
                $qty = (float) $data['item_qty'][$i];
                $price = (float) $data['item_price'][$i];
                $sum = $qty * $price;

                $stmt = $pdo->prepare("
                INSERT INTO invoice_items (invoice_id, title, quantity, unit, price, total)
                VALUES (?, ?, ?, ?, ?, ?)
            ");

                $stmt->execute([
                    $id,
                    $title,
                    $qty,
                    $data['item_unit'][$i],
                    $price,
                    $sum
                ]);
            }

            $pdo->commit();

            header("Location: /invoices");
            exit;
        } catch (\Exception $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo "Klaida atnaujinant sąskaitą: " . $e->getMessage();
        }
    }

    public function create()
    {
        $this->view('invoice/create', [
            'title' => 'Create Invoice'
        ]);
    }
    public function store()
    {
        $pdo = Database::connect();
        $pdo->beginTransaction();

        try {
            $data = $_POST;
            // TODO: Remake Validation 
            $stmt = $pdo->prepare("SELECT id FROM invoices WHERE invoice_number = ?");
            $stmt->execute([$data['invoice_number']]);

            if ($stmt->fetch()) {
                http_response_code(400);
                echo "Tokia sąskaita jau egzistuoja.";
                exit;
            }


            $data['user_id'] = Auth::id();
            $data['subtotal'] = (float) ($data['subtotal'] ?? 0);
            $data['vat_amount'] = (float) ($data['vat_amount'] ?? 0);
            $data['total'] = (float) ($data['total'] ?? 0);

            $stmt = $pdo->prepare("
                INSERT INTO invoices (
                    user_id, invoice_number, invoice_date, invoice_due,
                    seller_name, seller_code, seller_vat, seller_address, seller_bank, seller_iban,
                    buyer_name, buyer_code, buyer_vat, buyer_address,
                    vat_rate, subtotal, vat_amount, total
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $data['user_id'],
                $data['invoice_number'],
                $data['invoice_date'],
                $data['invoice_due'] ?: null,

                $data['seller_name'],
                $data['seller_code'],
                $data['seller_vat'],
                $data['seller_address'],
                $data['seller_bank'],
                $data['seller_iban'],

                $data['buyer_name'],
                $data['buyer_code'],
                $data['buyer_vat'],
                $data['buyer_address'],

                $data['vat_rate'] ?? 21,
                $data['subtotal'],
                $data['vat_amount'],
                $data['total']
            ]);

            $invoiceId = $pdo->lastInsertId();

            $items = [];
            foreach ($data['item_title'] as $i => $title) {
                $qty = (float) $data['item_qty'][$i];
                $price = (float) $data['item_price'][$i];
                $sum = $qty * $price;

                $stmt = $pdo->prepare("
                    INSERT INTO invoice_items (invoice_id, title, quantity, unit, price, total)
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([
                    $invoiceId,
                    $title,
                    $qty,
                    $data['item_unit'][$i],
                    $price,
                    $sum
                ]);

                $items[] = [
                    'title' => $title,
                    'quantity' => $qty,
                    'unit' => $data['item_unit'][$i],
                    'price' => $price,
                    'total' => $sum,
                ];
            }

            $pdo->commit();

            ob_start();
            include __DIR__ . '/../Views/invoice/pdf_template.php';
            $html = ob_get_clean();

            $dompdf = new \Dompdf\Dompdf();
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            $invoiceNumber = preg_replace('/[^a-zA-Z0-9-_]/', '_', $data['invoice_number'] ?? 'none');
            $date = $data['invoice_date'] ?? date('Y-m-d');
            $filename = "saskaita_{$invoiceNumber}_{$date}.pdf";

            $dompdf->stream($filename, ['Attachment' => true]);
            exit;
        } catch (\Exception $e) {
            $pdo->rollBack();
            http_response_code(500);
            echo "Klaida įrašant sąskaitą: " . $e->getMessage();
        }
    }

    public function invoices()
    {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM invoices WHERE user_id = ? ORDER BY invoice_date DESC");
        $stmt->execute([Auth::id()]);
        $invoices = $stmt->fetchAll();

        $this->view('invoice/index', [
            'title' => 'Sąskaitų sąrašas',
            'invoices' => $invoices
        ]);
    }


    public function show($id)
    {
        $pdo = Database::connect();

        $stmt = $pdo->prepare("SELECT * FROM invoices WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, Auth::id()]);
        $invoice = $stmt->fetch();

        if (!$invoice) {
            http_response_code(404);
            echo "Sąskaita nerasta.";
            return;
        }

        $stmt = $pdo->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");
        $stmt->execute([$id]);
        $items = $stmt->fetchAll();

        $this->view('invoice/create', [
            'title' => 'Redaguoti sąskaitą',
            'invoice' => $invoice,
            'items' => $items,
            'editing' => true
        ]);
    }
}
