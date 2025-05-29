<?php
require 'connection.php';
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;

$db = Connection::connect();

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$headerStyle = [
    'fill' => [
        'fillType'   => Fill::FILL_SOLID,
        'startColor' => ['argb' => 'FFDFF0D8']
    ],
    'font' => ['bold' => true]
];

$row = 1;

$sqlUsers = "
  SELECT u.id, u.last_name, u.first_name, u.middle_name,
         COUNT(e.id) AS cnt
    FROM users u
    JOIN equipment e 
      ON e.temp_responsible_user_id = u.id
   GROUP BY u.id
   ORDER BY u.last_name, u.first_name
"; 
$resUsers = $db->query($sqlUsers);

while ($user = $resUsers->fetch_assoc()) {
    // ФИО и общее количество
    $fio = trim("{$user['last_name']} {$user['first_name']} {$user['middle_name']}");
    $sheet->mergeCells("A{$row}:C{$row}");
    $sheet->setCellValue("A{$row}", $fio);
    $sheet->setCellValue("D{$row}", $user['cnt']);
    $sheet->getStyle("A{$row}:D{$row}")->applyFromArray($headerStyle);
    $row++;

    // Строка-заголовок столбцов
    $sheet->setCellValue("A{$row}", '№ п/п');
    $sheet->setCellValue("B{$row}", 'Основное средство');
    $sheet->setCellValue("C{$row}", 'Инвентарный номер');
    $sheet->setCellValue("D{$row}", 'Количество');
    $sheet->getStyle("A{$row}:D{$row}")->applyFromArray($headerStyle);
    $row++;

    // Получаем детали оборудования по пользователю
    $stmt = $db->prepare("
      SELECT name, inventory_number 
        FROM equipment 
       WHERE temp_responsible_user_id = ?
       ORDER BY name
    ");
    $stmt->bind_param('i', $user['id']);
    $stmt->execute();
    $stmt->bind_result($name, $inv);
    $i = 1;
    while ($stmt->fetch()) {
        $sheet->setCellValue("A{$row}", $i++);
        $sheet->setCellValue("B{$row}", $name);
        $sheet->setCellValue("C{$row}", $inv);
        $sheet->setCellValue("D{$row}", 1);
        $row++;
    }
    $stmt->close();

    $row++;
}


foreach (range('A', 'D') as $col) {
    $sheet->getColumnDimension($col)->setAutoSize(true);
}

$filename = 'act_transfer_' . date('Ymd_His') . '.xlsx';
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header("Content-Disposition: attachment; filename=\"{$filename}\"");
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
