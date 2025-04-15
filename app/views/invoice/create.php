<div class="max-w-md mx-auto mt-10 bg-white p-6 rounded shadow">
    <h1 class="text-2xl font-bold mb-4"><?= $title ?></h1>

    <form action="/invoice/save" method="POST" class="space-y-4">
        <div>
            <label class="block text-sm font-medium">Client name</label>
            <input type="text" name="client" required class="mt-1 w-full border rounded px-3 py-2" />
        </div>

        <div>
            <label class="block text-sm font-medium">Amount (€)</label>
            <input type="number" name="amount" step="0.01" required class="mt-1 w-full border rounded px-3 py-2" />
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Preview Invoice
        </button>
    </form>
</div>