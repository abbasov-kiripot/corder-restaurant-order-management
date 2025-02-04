<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- font awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />

    <link rel="shortcut icon" href="<?= $_ENV['BASE_URL'] ?>/public/images/32x32-icon.png" type="image/x-icon" />

    <link rel="stylesheet" href="<?= $_ENV['BASE_URL'] ?>/public/css/admin-panel-skeleton.css" />

    <link rel="stylesheet" href="<?= $_ENV['BASE_URL'] ?>/public/css/table.css" />

    <link rel="stylesheet" href="<?= $_ENV['BASE_URL'] ?>/public/css/switcher.css" />

    <title>Corder | Z Raporu</title>

</head>



<body>

    <!-- SIDEBAR START -->

    <nav>
<div class="logo">

    <div class="logo-image">

        <i class="fas fa-cookie-bite"></i>

    </div>

    <span class="logo-name">Corder Panel</span>

</div>

<div class="menu-items">

    <ul class="nav-links">

        <li>

            <a href="<?= $_ENV['BASE_URL'] ?>/admin/dashboard">

                <i class="fa-solid fa-gauge"></i>

                <span class="link-name">Aktiv Sifarişlər</span>

            </a>

        </li>

        <li>

            <a href="<?= $_ENV['BASE_URL'] ?>/admin/delivered">

                <i class="fa-solid fa-gauge"></i>

                <span class="link-name">Təhvil Verilənlər</span>

            </a>

        </li>

        <li>

            <a href="<?= $_ENV['BASE_URL'] ?>/admin/tables">

                <i class="fa-solid fa-magnifying-glass-chart"></i>

                <span class="link-name">Sistem</span>

            </a>

        </li>

        <li class="active">

            <a href="<?= $_ENV['BASE_URL'] ?>/admin/categories">

                <i class="fa-solid fa-book"></i>

                <span class="link-name">Kateqoriyalar</span>

            </a>

        </li>

        <li>

            <a href="<?= $_ENV['BASE_URL'] ?>/admin/products">

                <i class="fa-solid fa-book"></i>

                <span class="link-name">Məhsullar</span>

            </a>

        </li>

        <li>

            <a href="<?= $_ENV['BASE_URL'] ?>/admin/z-report">

                <i class="fa-solid fa-book"></i>

                <span class="link-name">Z Hesabatı</span>

            </a>

        </li>

    </ul>

    <ul class="logout-mode">

        <li>

            <a href="https://panel.kolayki.co/logout">

                <i class="fa-solid fa-arrow-right-from-bracket"></i>

                <span class="link-name">Çıxış</span>

            </a>

        </li>

        <li class="mode">

            <a href="javascript:void(0)">

                <i class="fa-regular fa-moon"></i>

                <span class="link-name">Tünd Rejim</span>

            </a>


            <div class="mode-toggle">

                <span class="switch"></span>

            </div>

        </li>

    </ul>

</div>

</nav>

    <!-- SIDEBAR END -->



    <!-- MAIN START -->

    <style>

        main {}



        iframe {

            border: 2px solid #686D76;

            border-radius: 10px;

        }



        /* .example-print {

            display: none;

        } */



        @media print {

            .unprintable {

                display: none;

            }



            .printable {

                display: block;

            }



            main {

                margin-left: 0px;

                padding: 0px;

            }

        }

    </style>

    <main>

    <div class="receipt">
        <h2>Qəbz</h2>
        <p><strong>Tarix/Saat:</strong> 19-06-2024 05:23:12</p>
        <p><strong>Qəbz növü:</strong> Z</p>
        <hr>
        <h3>SİFARİŞ</h3>
        <hr>
        <div class="order-details">
            <p><strong>Miqdar:</strong> 1</p>
            <p><strong>Birim Qiymət:</strong> ₼200.00</p>
            <p><strong>Məhsul:</strong> Səhər yeməyi boşqabı</p>
        </div>
        <hr>
        <p class="total">Ümumi: ₼200.00</p>
        <hr>
        <p class="footer"><strong>7-ci Bölük Fikrin Yeri</strong></p>
    </div>

    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .receipt {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 320px;
            text-align: center;
        }
        .receipt h2 {
            margin-bottom: 10px;
        }
        .receipt hr {
            border: 0;
            height: 1px;
            background: #ccc;
            margin: 10px 0;
        }
        .receipt .order-details {
            text-align: left;
        }
        .receipt .total {
            font-weight: bold;
            font-size: 20px;
            color: #d9534f;
        }
        .footer {
            margin-top: 15px;
            font-size: 14px;
            color: #555;
        }
    </style>

        <button class="unprintable" onclick="window.print();">BAS</button>

    </main>

    <!-- MAIN END -->

    <script>

        const baseUrl = "<?= $_ENV['BASE_URL'] ?>/";

    </script>

    <script type="module" src="<?= $_ENV['BASE_URL'] ?>/public/js/AdminMain.js"></script>

</body>



</html>