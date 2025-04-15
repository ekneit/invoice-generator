<?php

use App\Core\Auth;

if (Auth::check()) {
    header('Location: /');
    exit;
}
?>

<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-md bg-white shadow-lg rounded-xl p-8 border">
        <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Sukurti paskyrą</h2>

        <?php if (!empty($error)): ?>
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form action="/register" method="POST" class="space-y-5">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Vardas</label>
                <input type="text" name="name" id="name" required
                    class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">El. paštas</label>
                <input type="email" name="email" id="email" required
                    class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Slaptažodis</label>
                <input type="password" name="password" id="password" required
                    class="w-full border border-gray-300 px-4 py-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded-md font-medium text-lg hover:bg-blue-700 transition">
                Registruotis
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">Jau turite paskyrą? <a href="/login" class="text-blue-600 hover:underline">Prisijunkite</a></p>
    </div>
</div>