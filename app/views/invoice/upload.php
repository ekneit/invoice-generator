<div class="max-w-xl mx-auto mt-12 bg-white shadow rounded p-8">
    <h1 class="text-2xl font-bold mb-6 text-center">Įkelk sąskaitą (PDF / JPG / PNG)</h1>

    <form action="/upload" method="POST" enctype="multipart/form-data" class="space-y-6">
        <div>
            <label for="file" class="block font-medium mb-2">Pasirink failą</label>
            <input type="file" name="file" id="file" accept=".pdf,.jpg,.jpeg,.png" required
                class="w-full border border-gray-300 rounded px-4 py-2 file:bg-gray-100 file:border-0 file:mr-4 file:py-2 file:px-4 file:rounded file:cursor-pointer" />
        </div>

        <div class="text-center">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-700 transition">
                Gauti sąskaitą
            </button>
        </div>
    </form>
</div>