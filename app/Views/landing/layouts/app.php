<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="Untree.co">
    <link rel="shortcut icon" href="<?= base_url() ?>/favicon.ico" type="image/x-icon">

    <meta name="description" content="Bean bags sale" />
    <meta name="keywords" content="bean, bags, sale, produck, chair" />

    <!-- Bootstrap CSS -->
    <link href="<?= base_url() ?>css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="<?= base_url() ?>css/tiny-slider.css" rel="stylesheet">
    <link href="<?= base_url() ?>css/style.css" rel="stylesheet">
    <title><?= $this->renderSection('page_title'); ?></title>

    <?= $this->renderSection('head_css'); ?>
    <style>
        nav .container .collapse .custom-navbar-cta .active {
            color: #f9bf29;
        }
    </style>
</head>

<body>

    <?= $this->include('landing/layouts/partials/header'); ?>

    <?= $this->renderSection('content'); ?>

    <?= $this->include('landing/layouts/partials/footer'); ?>


    <script src="<?= base_url() ?>js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() ?>js/tiny-slider.js"></script>
    <script src="<?= base_url() ?>js/custom.js"></script>
    <?= $this->renderSection('footer_js'); ?>
</body>

</html>