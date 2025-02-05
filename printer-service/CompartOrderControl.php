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

//sideEffect!!!
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

    return $response = json_decode($response, true);
}


function kelimeleriFormatla(string $metin, int $satirUzunlugu = 16, string $boslukKarakteri = ' '): string
{
    $satirlar = [];

    // İlk 16 karakteri al
    $ilkSatir = mb_substr($metin, 0, $satirUzunlugu);
    $satirlar[] = $ilkSatir;

    // Geri kalan metni işle
    $geriKalanMetin = mb_substr($metin, $satirUzunlugu);

    // Metindeki her karakter için işlem yap
    for ($i = 0; $i < mb_strlen($geriKalanMetin); $i++) {
        // Eğer mevcut satır uzunluğuna ulaşıldıysa
        if (($i % $satirUzunlugu) === 0) {
            // 21 karakter boşluk ekle ve satıra ekle
            $satirlar[] = str_repeat($boslukKarakteri, 21);
        }

        // Karakteri ekle
        $satirlar[count($satirlar) - 1] .= $geriKalanMetin[$i];
    }
    // Son satıra bir alt satıra geçiş karakteri ekle
    $satirlar[count($satirlar) - 1] .= "\n";

    // Satırları birleştirerek döndür
    return implode(PHP_EOL, $satirlar);
}


function editFileToPrint(array $data): void
{
    if (isset($data['status_code'])) {
        if ($data['status_code'] == 'no_order') {
            die('no_order');
        }
    }

    $i = 0;
    <?php

    function writeToFile($fileName, $content) {
        $file = fopen($fileName, 'w');
        if ($file === false) {
            die("Unable to create file: $fileName");
        }
        fwrite($file, $content);
        fclose($file);
    }
    
    function formatOrderContent($data, $index) {
        $fileName = "compart-order-$index.txt";
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
    
    $i = 0;
    foreach ($data as $value) {
        $receiptContent = formatOrderContent($value, $i);
        $fileName = "compart-order-$i.txt";
        writeToFile($fileName, $receiptContent);
        $i++;
    }
    
    ?>
    

    printerExecute(count($data));
}
function printerExecute(int $count): void
{
    $apiKey = 'api_key';
    $data = [
        'api_key'     => $apiKey,
    ];
    $response = post('http://localhost/corder-restaurant-order-management/order-service/printer-status-update', $data);

    if ($response['status_code'] == 'success') {
        $printerName = "Microsoft Print to PDF"; // Kullanmak istediğin yazıcıyı buraya yaz.

        for ($i = 0; $i < $count; $i++) {
            $filePath = "C:\\compartPrinter\\compart-order-$i.txt";

            // PowerShell ile belirli bir yazıcıya yazdırma komutu
            $command = 'powershell.exe -Command "& {Start-Process -FilePath \'' . $filePath . '\' -ArgumentList \'/p /h\', \'' . $printerName . '\' -NoNewWindow -PassThru}"';

            exec($command);
        }
        die('success');
    } else {
        die('xxxx');
    }
}



editFileToPrint(compartOrderControlApi('http://localhost/corder-restaurant-order-management/order-service/last-order'));