<div class="max-w-5xl mx-auto mt-10 bg-white p-10 rounded shadow border border-gray-300 text-sm">
    <h1 class="text-2xl font-bold text-center mb-6">
        <?= isset($editing) ? 'Redaguoti sąskaitą' : 'Sąskaita faktūra' ?>
    </h1>

    <form action="<?= isset($editing) ? '/invoice/update/' . $invoice['id'] : '/invoice/save' ?>" method="POST" class="space-y-8" id="invoiceForm">
        <div class="grid grid-cols-3 gap-6 border-b pb-6">
            <div>
                <label class="font-medium">Sąskaitos numeris *</label>
                <input type="text" name="invoice_number" required class="input" placeholder="SF-00123" value="<?= $invoice['invoice_number'] ?? '' ?>">
            </div>
            <div>
                <label class="font-medium">Įrašymo data *</label>
                <input type="date" name="invoice_date" required value="<?= $invoice['invoice_date'] ?? date('Y-m-d') ?>" class="input">
            </div>
            <div>
                <label class="font-medium">Apmokėti iki</label>
                <input type="date" name="invoice_due" class="input" value="<?= $invoice['invoice_due'] ?? '' ?>">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-10">
            <div>
                <h2 class="text-lg font-semibold mb-2">Pardavėjas</h2>
                <div class="space-y-2">
                    <input name="seller_name" class="input" placeholder="Įmonės pavadinimas *" required value="<?= htmlspecialchars($invoice['seller_name'] ?? '') ?>">
                    <input name="seller_code" class="input" placeholder="Įmonės kodas *" required value="<?= $invoice['seller_code'] ?? '' ?>">
                    <input name="seller_vat" class="input" placeholder="PVM kodas" value="<?= $invoice['seller_vat'] ?? '' ?>">
                    <input name="seller_address" class="input" placeholder="Adresas *" required value="<?= $invoice['seller_address'] ?? '' ?>">
                    <input name="seller_bank" class="input" placeholder="Banko pavadinimas" value="<?= $invoice['seller_bank'] ?? '' ?>">
                    <input name="seller_iban" class="input" placeholder="IBAN sąskaita" value="<?= $invoice['seller_iban'] ?? '' ?>">
                </div>
            </div>
            <div>
                <h2 class="text-lg font-semibold mb-2">Pirkėjas</h2>
                <div class="space-y-2">
                    <input name="buyer_name" class="input" placeholder="Pavadinimas / vardas *" required value="<?= htmlspecialchars($invoice['buyer_name'] ?? '') ?>">
                    <input name="buyer_code" class="input" placeholder="Įmonės kodas" value="<?= $invoice['buyer_code'] ?? '' ?>">
                    <input name="buyer_vat" class="input" placeholder="PVM kodas" value="<?= $invoice['buyer_vat'] ?? '' ?>">
                    <input name="buyer_address" class="input" placeholder="Adresas *" required value="<?= $invoice['buyer_address'] ?? '' ?>">
                </div>
            </div>
        </div>

        <div>
            <h2 class="text-lg font-semibold mb-2">Prekės / paslaugos</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border border-gray-300" id="itemsTable">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-3 py-2 border">#</th>
                            <th class="px-3 py-2 border">Pavadinimas *</th>
                            <th class="px-3 py-2 border">Kiekis *</th>
                            <th class="px-3 py-2 border">Vnt.</th>
                            <th class="px-3 py-2 border">Kaina (€) *</th>
                            <th class="px-3 py-2 border">Suma (€)</th>
                            <th class="px-3 py-2 border text-center">🗑️</th>
                        </tr>
                    </thead>
                    <tbody id="itemsBody">
                        <?php if (!empty($items)): ?>
                            <?php foreach ($items as $i => $item): ?>
                                <tr>
                                    <td class="px-3 py-2 border text-center"><?= $i + 1 ?></td>
                                    <td class="px-3 py-2 border">
                                        <input name="item_title[]" required class="input" value="<?= htmlspecialchars($item['title']) ?>">
                                    </td>
                                    <td class="px-3 py-2 border">
                                        <input type="number" name="item_qty[]" value="<?= $item['quantity'] ?>" min="1" class="input qty" required>
                                    </td>
                                    <td class="px-3 py-2 border">
                                        <input name="item_unit[]" class="input" value="<?= htmlspecialchars($item['unit']) ?>">
                                    </td>
                                    <td class="px-3 py-2 border">
                                        <input
                                            type="number"
                                            name="item_price[]"
                                            value="<?= number_format($item['price'], 2) ?>"
                                            step="0.01"
                                            class="input price"
                                            required
                                            oninvalid="this.setCustomValidity('Įveskite kainą')"
                                            oninput="this.setCustomValidity('')">
                                    </td>
                                    <td class="px-3 py-2 border text-right sum">0.00</td>
                                    <td class="px-3 py-2 border text-center">
                                        <button type="button" class="text-red-500 remove-row">✕</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td class="px-3 py-2 border text-center">1</td>
                                <td class="px-3 py-2 border">
                                    <input name="item_title[]" required class="input" placeholder="Paslauga / prekė">
                                </td>
                                <td class="px-3 py-2 border">
                                    <input type="number" name="item_qty[]" placeholder="1" min="1" class="input qty" required>
                                </td>
                                <td class="px-3 py-2 border">
                                    <input name="item_unit[]" class="input" value="vnt.">
                                </td>
                                <td class="px-3 py-2 border">
                                    <input
                                        type="number"
                                        name="item_price[]"
                                        placeholder="0.00"
                                        step="0.01"
                                        class="input price"
                                        required
                                        oninvalid="this.setCustomValidity('Įveskite kainą')"
                                        oninput="this.setCustomValidity('')">
                                </td>
                                <td class="px-3 py-2 border text-right sum">0.00</td>
                                <td class="px-3 py-2 border text-center">
                                    <button type="button" class="text-red-500 remove-row">✕</button>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>

                </table>
            </div>

            <div class="text-right mt-4">
                <button type="button" id="addItemBtn" class="bg-gray-200 px-4 py-2 rounded hover:bg-gray-300 text-sm">
                    ➕ Pridėti eilutę
                </button>
            </div>
        </div>

        <div class="text-right flex items-center justify-end gap-3 pt-6">
            <label for="vatRate" class="text-sm">PVM tarifas:</label>
            <select name="vat_rate" id="vatRate" class="border border-gray-300 rounded px-3 py-2 text-sm">
                <option value="21" <?= (isset($invoice['vat_rate']) && $invoice['vat_rate'] == 21) ? 'selected' : '' ?>>21%</option>
                <option value="9" <?= (isset($invoice['vat_rate']) && $invoice['vat_rate'] == 9) ? 'selected' : '' ?>>9%</option>
                <option value="5" <?= (isset($invoice['vat_rate']) && $invoice['vat_rate'] == 5) ? 'selected' : '' ?>>5%</option>
                <option value="0" <?= (isset($invoice['vat_rate']) && $invoice['vat_rate'] == 0) ? 'selected' : '' ?>>0%</option>
                <option value="none" <?= (isset($invoice['vat_rate']) && $invoice['vat_rate'] === 'none') ? 'selected' : '' ?>>Be PVM</option>
            </select>
        </div>

        <div class="text-right text-sm pt-4 space-y-1 border-t mt-4">
            <div>📋 Tarpinė suma: <span id="subtotal">0.00</span> €</div>
            <div id="vatRow">➕ PVM (<span id="vatPercent">21</span>%): <span id="vat">0.00</span> €</div>
            <div class="font-bold text-lg pt-2">💶 Galutinė suma: <span id="totalSum">0.00</span> €</div>
        </div>

        <input type="hidden" name="subtotal" id="hiddenSubtotal">
        <input type="hidden" name="vat_amount" id="hiddenVat">
        <input type="hidden" name="total" id="hiddenTotal">

        <div class="text-center pt-6">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded shadow text-lg">
                <?= isset($editing) ? 'Atnaujinti sąskaitą' : 'Generuoti sąskaitą' ?>
            </button>
        </div>
    </form>
</div>

<style>
    .input {
        @apply border border-gray-300 px-3 py-2 rounded w-full;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const itemsBody = document.getElementById('itemsBody');
        const addItemBtn = document.getElementById('addItemBtn');
        const totalSumEl = document.getElementById('totalSum');
        const subtotalEl = document.getElementById('subtotal');
        const vatEl = document.getElementById('vat');
        const vatRateSelect = document.getElementById('vatRate');
        const vatRow = document.getElementById('vatRow');
        const vatPercent = document.getElementById('vatPercent');


        function updateSums() {
            let subtotal = 0;
            itemsBody.querySelectorAll('tr').forEach((row, index) => {
                row.querySelector('td:first-child').textContent = index + 1;
                const qty = parseFloat(row.querySelector('.qty')?.value || 0);
                const price = parseFloat(row.querySelector('.price')?.value || 0);
                const sum = qty * price;
                row.querySelector('.sum').textContent = sum.toFixed(2);
                subtotal += sum;
            });

            const selectedRate = vatRateSelect.value;
            let vat = 0;

            if (selectedRate !== 'none') {
                const rate = parseFloat(selectedRate);
                vat = subtotal * (rate / 100);
                vatRow.style.display = 'block';
                vatPercent.textContent = rate;
            } else {
                vatRow.style.display = 'none';
            }

            const total = subtotal + vat;

            subtotalEl.textContent = subtotal.toFixed(2);
            vatEl.textContent = vat.toFixed(2);
            totalSumEl.textContent = total.toFixed(2);

            document.getElementById('hiddenSubtotal').value = subtotal.toFixed(2);
            document.getElementById('hiddenVat').value = vat.toFixed(2);
            document.getElementById('hiddenTotal').value = total.toFixed(2);
        }

        addItemBtn.addEventListener('click', () => {
            const newRow = itemsBody.querySelector('tr').cloneNode(true);
            newRow.querySelectorAll('input').forEach(input => {
                if (input.name.includes('qty')) {
                    input.value = 1;
                } else if (input.name.includes('price')) {
                    input.value = '';
                } else {
                    input.value = '';
                }
            });
            itemsBody.appendChild(newRow);
            updateSums();
        });


        itemsBody.addEventListener('input', (e) => {
            if (e.target.classList.contains('qty') || e.target.classList.contains('price')) {
                updateSums();
            }
        });

        itemsBody.addEventListener('click', (e) => {
            if (e.target.classList.contains('remove-row')) {
                const rows = itemsBody.querySelectorAll('tr');
                if (rows.length > 1) {
                    e.target.closest('tr').remove();
                    updateSums();
                }
            }
        });

        vatRateSelect.addEventListener('change', updateSums);

        updateSums();
    });
</script>