<html lang='en-GB'>
<head>
    <?= $this->Html->charset() ?>
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prontostoreus - Self-storage Application Invoice</title>
    <style>
        body {
            color: black !important;
        }
        tr:last-child > td {
            border-bottom: 1px solid #ddd !important;
        }
        table, tr, td, th, tbody, thead, tfoot {
            page-break-inside: avoid !important;
        }
    </style>
</head>
    <body>
        <?= $this->fetch('content') ?>
    </body>
</html>