<?php
require 'connection.php';
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

$db = Connection::connect();

$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if (!$userId) {
    $res = $db->query("SELECT id, last_name, first_name, middle_name FROM users");
    echo '<h2>Сформировать акт приема-передачи оборудования</h2>';
    echo '<form method="get">';
    echo '<label for="user_id">Сотрудник:</label> ';
    echo '<select name="user_id" id="user_id">';
    while ($u = $res->fetch_assoc()) {
        $fio = trim("{$u['last_name']} {$u['first_name']} {$u['middle_name']}");
        echo '<option value="'. $u['id'] .'">'. htmlspecialchars($fio) .'</option>';
    }
    echo '</select> ';
    echo '<button type="submit">Сформировать акт</button>';
    echo '</form>';
    exit;
}

$stmt = $db->prepare('SELECT last_name, first_name, middle_name FROM users WHERE id = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($last, $first, $middle);
if (!$stmt->fetch()) die('Сотрудник не найден');
$stmt->close();
$fio = trim("{$last} {$first} {$middle}");

// Получение переданного оборудования
$stmt2 = $db->prepare(
    'SELECT name, inventory_number, cost
       FROM equipment
      WHERE temp_responsible_user_id = ?'
);
$stmt2->bind_param('i', $userId);
$stmt2->execute();
$stmt2->bind_result($name, $invNumber, $cost);
$items = [];
while ($stmt2->fetch()) {
    $items[] = [
        'name'   => $name,
        'serial' => $invNumber,
        'cost'   => number_format($cost, 2, '.', ' ') . ' руб.'
    ];
}
$stmt2->close();

$phpWord = new PhpWord();
$section = $phpWord->addSection([
    'marginTop'  => 600,
    'marginLeft' => 600,
    'marginRight'=> 600,
]);

$section->addText('АКТ', ['bold'=>true, 'size'=>14], ['alignment'=>Jc::CENTER]);
$section->addText('приема-передачи оборудования', null, ['alignment'=>Jc::CENTER]);
$section->addTextBreak(1);

$city = 'Пермь';
$now  = new DateTime();
$day  = $now->format('d');
$monthNames = ['января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
$month = $monthNames[(int)$now->format('n') - 1];
$year  = $now->format('Y');
$dateTable = $section->addTable();
$dateTable->addRow();
$dateTable->addCell(4500)->addText("г. {$city}", null, ['alignment'=>Jc::START]);
$dateTable->addCell(4500)->addText("{$day}.{$now->format('m')}.{$year}", null, ['alignment'=>Jc::END]);
$section->addTextBreak(1);

$institution = 'КГАПОУ Пермский Авиационный техникум им. А.Д. Швецова';
$section->addText(
    "{$institution} в целях обеспечения необходимым оборудованием для исполнения должностных обязанностей\n"
   ."передаёт сотруднику {$fio}, а сотрудник принимает от учебного учреждения следующее оборудование:",
    null,
    ['alignment'=>Jc::BOTH]
);
$section->addTextBreak(1);

$phpWord->addTableStyle('EquipTable', ['borderSize'=>6, 'borderColor'=>'999999']);
$table = $section->addTable('EquipTable');

$table->addRow();
$table->addCell(1200)->addText('№', ['bold'=>true]);
$table->addCell(6000)->addText('Описание оборудования', ['bold'=>true]);
$table->addCell(3000)->addText('Серийный номер', ['bold'=>true]);
$table->addCell(2000)->addText('Стоимость', ['bold'=>true]);

foreach ($items as $i => $it) {
    $table->addRow();
    $table->addCell(1200)->addText((string)($i+1));
    $table->addCell(6000)->addText($it['name']);
    $table->addCell(3000)->addText($it['serial']);
    $table->addCell(2000)->addText($it['cost']);
}
$section->addTextBreak(2);

$sign = $section->addTable();
$sign->addRow();
$sign->addCell(8000)->addText($fio);
$sign->addCell(4000)->addText('__________________________');
$sign->addCell(2000)->addText('__________ 20__ г.');

$fileName = "act_transfer_{$userId}_" . date('Ymd') . ".docx";
header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="'. $fileName .'"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save('php://output');

Connection::close($db);
exit;
?>
