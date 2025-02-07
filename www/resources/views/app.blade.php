<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @routes
    <?php use Illuminate\Support\Facades\URL; ?>
    <?php $manifest = json_decode(file_get_contents(public_path('manifest.json')), true); ?>
    <?php if($manifest): ?>
<!--        <script type="module" src="--><?php //=asset('build/'.$manifest['resources/js/app.tsx']['file'])?><!--"></script>-->
<!--        <link rel="stylesheet" href="--><?php //=asset('build/'.$manifest['resources/js/app.tsx']['css'][0])?><!--" />-->
    <?php endif;?>




    <?php if(false === app()->environment('production')): ?>
        <?php $url = str_replace(['http:', 'https:'], '', env('APP_URL')) ?>
        <script type="module">
            import RefreshRuntime from '<?php echo $url ?>:5173/@react-refresh';
            RefreshRuntime.injectIntoGlobalHook(window);
            window.$RefreshReg$ = () => {};
            window.$RefreshSig$ = () => (type) => type;
            window.__vite_plugin_react_preamble_installed__ = true;
        </script>
        <script type="module" src="<?php echo $url ?>:5173/@@vite/client"></script>
        <script type="module" src="<?php echo $url ?>:5173/resources/js/app.tsx"></script>
        <script type="module" src="<?php echo $url ?>:5173/resources/js/Pages/<?php echo $page['component'] ?>.tsx"></script>
    <?php else: ?>
        <link rel="stylesheet" href="<?= asset('assets/css/app.css') ?>">
        <script type="module" src="<?= asset('assets/js/app.js') ?>" defer></script>
    <?php endif; ?>

    <script>
        console.log('asset', '<?php echo asset('assets/app.css') ?>')
        console.log('url', '<?php echo $url; ?>')
    </script>

</head>
<body class="font-sans antialiased">
@inertia
</body>
</html>
