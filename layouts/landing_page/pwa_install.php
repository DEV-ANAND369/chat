<?php if (Registry::load('settings')->add_to_home_screen_library === 'enable') {
    ?>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/philfung/add-to-homescreen@2.1/dist/add-to-homescreen.min.css" />
    <script src="https://cdn.jsdelivr.net/gh/philfung/add-to-homescreen@2.1/dist/add-to-homescreen.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            window.AddToHomeScreenInstance = window.AddToHomeScreen({
                appName: '<?php echo Registry::load('settings')->pwa_name ?>',
                appNameDisplay: '<?php echo Registry::load('settings')->pwa_display ?>',
                appIconUrl: 'assets/files/defaults/pwa_icon-192x192.png',
                assetUrl: 'assets/files/defaults/',
                maxModalDisplayCount: -1
            });

            setTimeout(function() {
                showAddToHomeScreen();
            }, 5000);

        });

        function showAddToHomeScreen() {
            const maxShowCount = 4;

            if (typeof(Storage) !== "undefined") {
                const lastShown = localStorage.getItem('lastAddToHomeScreenTime');
                const showCount = localStorage.getItem('addToHomeScreenShowCount') || 0;
                const currentTime = new Date().getTime();

                if (!lastShown || ((currentTime - lastShown) / 1000 > 3600)) {
                    localStorage.setItem('addToHomeScreenShowCount', 0);
                    localStorage.setItem('lastAddToHomeScreenTime', currentTime);
                }

                if (showCount < maxShowCount) {
                    window.AddToHomeScreenInstance.show();
                    localStorage.setItem('addToHomeScreenShowCount', parseInt(showCount) + 1);
                }

            } else {
                window.AddToHomeScreenInstance.show();
            }
        }
    </script>

    <?php
} else {
    ?>

    <script type="module">
        import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwainstall';
        const el = document.createElement('pwa-update');
        document.body.appendChild(el);
    </script>

    <?php
} ?>