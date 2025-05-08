<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Spice Shop Admin' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="../assets/style-modern-spice.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/sweetalert.js"></script>
</head>

<body class="bg-gray-100 text-gray-800 font-sans">
    <?php include 'navbar.php'; ?>
    <div class="flex">
        <?php include 'sidebar.php'; ?>
        <!-- Main Content -->
        <main class="flex-1 p-6">