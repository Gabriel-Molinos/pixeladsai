<div class="drawer-side z-40">
    <label for="drawer" class="drawer-overlay"></label>
    
    <aside class="w-64 h-full bg-base-100 flex flex-col justify-between p-4">

        <div>
            <h2 class="text-xl font-bold text-primary mb-6">
                Pixel Ads AI
            </h2>

            <ul class="menu gap-2">
                <li>
                    <a href="/dashboard" class="<?= ($_SERVER['REQUEST_URI'] == '/dashboard') ? 'active' : '' ?>">
                        📊 Dashboard
                    </a>
                </li>

                <li>
                    <a href="/report" class="<?= ($_SERVER['REQUEST_URI'] == '/report') ? 'active' : '' ?>">
                        📄 Report
                    </a>
                </li>
            </ul>
        </div>

        <div class="text-xs opacity-50">
            © <?= date('Y') ?> Pixel Ads
        </div>

    </aside>
</div>