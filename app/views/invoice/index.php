<div class="max-w-6xl mx-auto mt-12 px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">📄 Sąskaitos</h1>
        <a href="/invoice/create" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700 transition">
            ➕ Nauja sąskaita
        </a>
    </div>

    <div class="overflow-x-auto bg-white rounded-xl shadow border">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Data</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Numeris</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Pirkėjas</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Suma</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-600">Veiksmai</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($invoices as $invoice): ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($invoice['invoice_date']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($invoice['invoice_number']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-700"><?= htmlspecialchars($invoice['buyer_name']) ?></td>
                        <td class="px-6 py-4 text-sm text-gray-700 font-semibold"><?= number_format($invoice['total'], 2) ?> €</td>
                        <td class="px-6 py-4 text-sm">
                            <a href="/invoice/view/<?= $invoice['id'] ?>"
                                class="inline-block text-blue-600 hover:text-blue-800 font-medium">
                                🔍 Peržiūrėti
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>