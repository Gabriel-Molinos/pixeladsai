<div class="w-full bg-base-100 border-b border-base-300">
    <div class="navbar px-4 lg:px-6 max-w-none overflow-visible">

        <div class="flex-none lg:hidden">
            <label for="drawer" class="btn btn-square btn-ghost">☰</label>
        </div>

        <div class="flex-1">
            <h1 class="text-lg font-semibold">
                <?= ucfirst(trim($_SERVER['REQUEST_URI'], '/')) ?: 'Dashboard' ?>
            </h1>
        </div>

        <div class="flex-none flex items-center gap-4">

            <div class="hidden md:flex items-center gap-2">
                <span class="badge badge-success badge-sm"></span>
                <span class="text-sm">Online</span>
            </div>

            <?php partial('dropdown-user'); ?>

        </div>

    </div>
</div>