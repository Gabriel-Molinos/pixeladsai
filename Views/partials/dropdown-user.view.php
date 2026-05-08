<div class="dropdown dropdown-end">

    <div tabindex="0" class="avatar placeholder cursor-pointer">
        <div class="bg-primary text-center text-primary-content rounded-full w-7">
            <span>
                <?= strtoupper(substr(auth()['google_name'], 0, 1)) ?>
            </span>
        </div>
    </div>

    <ul tabindex="0" class="dropdown-content z-[999] menu p-3 shadow bg-base-100 rounded-box w-80 mt-3 right-0">

        <li class="mb-2">
            <div class="flex flex-col">
                <span class="font-bold"><?= auth()['google_name'] ?></span>
                <span class="text-xs opacity-60"><?= auth()['google_email'] ?></span>
            </div>
        </li>

        <div class="divider my-1"></div>

        <li class="menu-title">
            <span>Contas Google Ads</span>
        </li>

        <?php if(!empty($_SESSION['accounts'])): ?>
            <?php foreach($_SESSION['accounts'] as $acc): ?>
                <li>
                    <a href="/select-account?id=<?= $acc ?>"
                       class="<?= (isset($_SESSION['selected_account']) && $_SESSION['selected_account'] == $acc) ? 'active' : '' ?>">
                        <?= $acc ?>
                        <span class="badge badge-outline">Ads</span>
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>
                <span class="text-xs opacity-50">Nenhuma conta</span>
            </li>
        <?php endif; ?>

        <div class="divider my-1"></div>

        <li>
            <a href="/logout" class="text-error">🚪 Sair</a>
        </li>

    </ul>
</div>