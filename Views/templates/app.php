<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'App' ?></title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@5" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/daisyui@5/themes.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body>

<div class="drawer lg:drawer-open">
    
    <input id="drawer" type="checkbox" class="drawer-toggle" />

    <?php partial('sidebar'); ?>

    <div class="drawer-content flex flex-col">

        <?php partial('navbar'); ?>

        <main class="p-4 md:p-6 bg-base-200 min-h-screen">
            <?= $content ?? '' ?>
        </main>

    </div>

</div>

</body>
</html>