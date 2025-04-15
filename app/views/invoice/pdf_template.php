<!DOCTYPE html>
<html lang="lt">

<head>
    <meta charset="UTF-8">
    <title>Sąskaita faktūra</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 18px;
        }

        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 30px;
            table-layout: fixed;
        }

        .info-box {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            box-sizing: border-box;
        }


        .info-box h3 {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .info-box p {
            margin: 2px 0;
            line-height: 1.4;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11px;
        }

        thead {
            background: #f2f2f2;
        }

        th,
        td {
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        th {
            font-weight: 600;
            text-align: left;
        }

        .summary {
            margin-top: 30px;
            width: 100%;
            font-size: 12px;
        }

        .summary td {
            padding: 5px;
            text-align: right;
        }

        .summary .label {
            text-align: left;
            font-weight: bold;
        }

        .total {
            font-weight: bold;
            font-size: 13px;
        }
    </style>
</head>

<body>
    <h2>Sąskaita faktūra</h2>

    <p><strong>Sąskaitos numeris:</strong> <?= htmlspecialchars($data['invoice_number']) ?></p>
    <p><strong>Išrašymo data:</strong> <?= htmlspecialchars($data['invoice_date']) ?></p>

    <div class="info-row">
        <div class="info-box">
            <h3>Pardavėjas</h3>
            <p><?= htmlspecialchars($data['seller_name']) ?></p>
            <p>Kodas: <?= htmlspecialchars($data['seller_code']) ?></p>
            <p>PVM kodas: <?= htmlspecialchars($data['seller_vat']) ?></p>
            <p>Adresas: <?= htmlspecialchars($data['seller_address']) ?></p>
            <p>Bankas: <?= htmlspecialchars($data['seller_bank']) ?></p>
            <p>IBAN: <?= htmlspecialchars($data['seller_iban']) ?></p>
        </div>

        <div class="info-box">
            <h3>Pirkėjas</h3>
            <p><?= htmlspecialchars($data['buyer_name']) ?></p>
            <p>Kodas: <?= htmlspecialchars($data['buyer_code']) ?></p>
            <p>PVM kodas: <?= htmlspecialchars($data['buyer_vat']) ?></p>
            <p>Adresas: <?= htmlspecialchars($data['buyer_address']) ?></p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 45%;">Pavadinimas</th>
                <th style="width: 10%;">Kiekis</th>
                <th style="width: 10%;">Vnt.</th>
                <th style="width: 15%;">Kaina (€)</th>
                <th style="width: 15%;">Suma (€)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $total = 0;
            foreach ($data['item_title'] as $i => $title):
                $qty = (float) $data['item_qty'][$i];
                $price = (float) $data['item_price'][$i];
                $sum = $qty * $price;
                $total += $sum;
            ?>
                <tr>
                    <td><?= $i + 1 ?></td>
                    <td><?= htmlspecialchars($title) ?></td>
                    <td><?= $qty ?></td>
                    <td><?= htmlspecialchars($data['item_unit'][$i]) ?></td>
                    <td><?= number_format($price, 2) ?></td>
                    <td><?= number_format($sum, 2) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php
    $vat = 0;
    $rate = 0;
    if (isset($data['vat_rate']) && $data['vat_rate'] !== 'none') {
        $rate = (float)$data['vat_rate'];
        $vat = $total * $rate / 100;
    }
    $grandTotal = $total + $vat;
    ?>

    <table class="summary">
        <tr>
            <td class="label">Suma be PVM:</td>
            <td><?= number_format($total, 2) ?> €</td>
        </tr>
        <?php if ($vat > 0): ?>
            <tr>
                <td class="label">PVM (<?= $rate ?>%):</td>
                <td><?= number_format($vat, 2) ?> €</td>
            </tr>
        <?php endif; ?>
        <tr class="total">
            <td class="label">Viso:</td>
            <td><?= number_format($grandTotal, 2) ?> €</td>
        </tr>
    </table>
</body>

</html>