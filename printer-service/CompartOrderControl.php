<?php

declare(strict_types=1);

function compartOrderControlApi($url): array
{
    $apiKey = 'api_key';
    $data = [
        'api_key'     => $apiKey,
    ];

    return post($url, $data);
}

function post(string $url, array $data): array
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded; charset=utf-8',
        'Accept-Charset: utf-8'
    ]);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function kelimeleriFormatla(string $metin, int $satirUzunlugu = 16, string $boslukKarakteri = ' '): string
{
    // Existing implementation remains the same
    $satirlar = [];
    $ilkSatir = mb_substr($metin, 0, $satirUzunlugu);
    $satirlar[] = $ilkSatir;
    $geriKalanMetin = mb_substr($metin, $satirUzunlugu);

    for ($i = 0; $i < mb_strlen($geriKalanMetin); $i++) {
        if (($i % $satirUzunlugu) === 0) {
            $satirlar[] = str_repeat($boslukKarakteri, 21);
        }
        $satirlar[count($satirlar) - 1] .= $geriKalanMetin[$i];
    }
    $satirlar[count($satirlar) - 1] .= "\n";

    return implode(PHP_EOL, $satirlar);
}

function writeToFile($fileName, $content) {
    $file = fopen($fileName, 'w');
    if ($file === false) {
        die("Unable to create file: $fileName");
    }
    fwrite($file, $content);
    fclose($file);
}

function formatOrderContent($data, $index) {
    // Existing implementation remains the same
    $unixTimestamp = strtotime($data['order_time']);
    $orderNote = "NOT: " . $data['order_note'] . "\n\n";

    $receiptContent = "Tarih/Saat:  " . date('d-m-Y H:i:s', $unixTimestamp) . "\n" .
                      "Sipariş No:  " . $data['id'] . "\n" .
                      "Masa      :  " . $data['table_area'] . " - " . $data['table_number'] . "\n" .
                      "Garson    :  " . ($data['custom'] == 'y' ? 'Yazıcı Servisi' : $data['waiter_name']) . "\n\n\n" .
                      "SİPARİŞ\n\n" .
                      $orderNote .
                      "_____________________________________\n" .
                      "Miktar  Birim Fiyat  Ürün\n" .
                      "‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾\n";

    $order = json_decode($data['order_content'], true);
    $totalPrice = 0;
    
    foreach ($order as $item) {
        $receiptContent .= "  " . $item['amount'] . "      ₺" . $item['product_price'] . ".00     " . kelimeleriFormatla($item['product_name']) . "\n";
        $totalPrice += $item['amount'] * $item['product_price'];
    }

    $receiptContent .=
        "_____________________________________\n" .
        "Toplam:  ₺$totalPrice.00\n" .
        "‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾\n\n" .
        "            Özgür'ün Yeri";

    return $receiptContent;
}

function getPrinterList(): array {
    // Get available printers (using Windows command)
    exec('powershell.exe -Command "Get-Printer | Select-Object Name"', $printerOutput);
    
    // Skip the first two lines (header lines)
    $printers = array_slice($printerOutput, 2);
    
    // Clean up printer names
    $printerList = array_map('trim', $printers);
    
    return $printerList;
}

function selectPrinter(array $printers): string {
    echo "Lütfen yazdırmak istediğiniz yazıcıyı seçin:\n";
    foreach ($printers as $index => $printer) {
        echo ($index + 1) . ". $printer\n";
    }
    
    // Get user input
    echo "Yazıcı numarasını girin: ";
    $handle = fopen("php://stdin", "r");
    $selection = trim(fgets($handle));
    
    // Validate selection
    if (!is_numeric($selection) || $selection < 1 || $selection > count($printers)) {
        die("Geçersiz yazıcı seçimi. İşlem iptal edildi.\n");
    }
    
    return $printers[$selection - 1];
}

function printerExecute(int $count, string $selectedPrinter): void
{
    $apiKey = 'api_key';
    $data = [
        'api_key'     => $apiKey,
    ];
    $response = post('http://localhost/corder-restaurant-order-management/order-service/printer-status-update', $data);

    if ($response['status_code'] == 'success') {
        for ($i = 0; $i < $count; $i++) {
            $command = 'powershell.exe -Command "Start-Process \'C:\compartPrinter\compart-order-' . $i . '.txt\' -WindowStyle Hidden –Verb Print -PrinterName \'' . $selectedPrinter . '\'"';
            exec($command);
        }
        die('success');
    } else {
        die('xxxx');
    }
}

function editFileToPrint(array $data): void
{
    if (isset($data['status_code'])) {
        if ($data['status_code'] == 'no_order') {
            die('no_order');
        }
    }

    // Prepare order files
    $i = 0;
    foreach ($data as $value) {
        $receiptContent = formatOrderContent($value, $i);
        $fileName = "compart-order-$i.txt";
        writeToFile($fileName, $receiptContent);
        $i++;
    }

    // Get and select printer
    $printers = getPrinterList();
    $selectedPrinter = selectPrinter($printers);

    // Print with selected printer
    printerExecute(count($data), $selectedPrinter);
}

// Execute the main function
editFileToPrint(compartOrderControlApi('http://localhost/corder-restaurant-order-management/order-service/last-order'));