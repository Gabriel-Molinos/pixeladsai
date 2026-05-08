<?php ob_start(); ?>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h2 class="card-title text-sm">Contas</h2>
            <p class="text-3xl font-bold"><?= $qtd ?? 0 ?></p>
        </div>
    </div>

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h2 class="card-title text-sm">Campanhas</h2>
            <p class="text-3xl font-bold"><?= $totalCampaigns ?? 0 ?></p>
        </div>
    </div>

    <div class="card bg-base-100 shadow">
        <div class="card-body">
            <h2 class="card-title text-sm">Conversões</h2>
            <p class="text-3xl font-bold"><?= $conversions ?? 0 ?></p>
        </div>
    </div>

    <div class="card bg-base-100 shadow">
        <div class="card-body flex items-center justify-center">
            <span class="badge badge-success badge-lg">Sistema OK</span>
        </div>
    </div>

</div>

<?php $content = ob_get_clean(); ?>

<?php require BASE_PATH . '/Views/templates/app.php'; ?>