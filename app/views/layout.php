<?php

use App\Core\Auth;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?= $title ?? 'Invoice App' ?></title>

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 min-h-screen">


    <nav class="bg-white shadow mb-6">
        <div class="max-w-7xl mx-auto px-4 py-3 flex justify-between items-center">
            <div class="text-xl font-bold text-gray-700"><a href="/">Invoice Generator</a></div>
            <div class="space-x-4">

                <?php if (Auth::check()): ?>
                    <a href="/invoice/create" class="text-gray-700 hover:text-blue-600 font-medium">Sukurti sąskaitą</a>
                    <a href="/invoices" class="text-gray-700 hover:text-blue-600 font-medium">Sąskaitų sąrašas</a>
                    <a href="/upload" class="text-gray-700 hover:text-blue-600 font-medium">Įkelk sąskaitą AI</a>
                    <a href="/logout" class="text-red-600 hover:underline font-medium">Atsijungti</a>
                <?php else: ?>
                    <a href="/login" class="text-gray-700 hover:text-blue-600 font-medium">Prisijungti</a>
                    <a href="/register" class="text-gray-700 hover:text-blue-600 font-medium">Registruotis</a>
                <?php endif; ?>

            </div>
        </div>
    </nav>


    <div class="max-w-6xl mx-auto px-4">
        <?= $content ?? '' ?>
    </div>

</body>

</html>