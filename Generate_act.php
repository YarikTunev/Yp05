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
    echo '<h2>Сформировать акт приёма-передачи оборудования</h2>';
    echo '<form method="get">';
    echo '<label for="user_id">Выберите сотрудника:</label> ';
    echo '<select name="user_id" id="user_id">';
    while ($u = $res->fetch_assoc()) {
        $fio = trim($u['last_name'] . ' ' . $u['first_name'] . ' ' . $u['middle_name']);
        echo '<option value="' . $u['id'] . '">' . htmlspecialchars($fio) . '</option>';
    }
    echo '</select> ';
    echo '<button type="submit">Сформировать акт</button>';
    echo '</form>';
    exit;
}

$stmt = $db->prepare('SELECT last_name, first_name, middle_name FROM users WHERE id = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($lastName, $firstName, $middleName);
if (!$stmt->fetch()) {
    die('Сотрудник не найден');
}
$stmt->close();
$fio = trim("{$lastName} {$firstName} {$middleName}");

$stmt2 = $db->prepare('SELECT name, inventory_number, cost FROM equipment WHERE temp_responsible_user_id = ?');
$stmt2->bind_param('i', $userId);
$stmt2->execute();
$stmt2->bind_result($eqName, $invNumber, $eqCost);
$equipment = [];
while ($stmt2->fetch()) {
    $equipment[] = [
        'name'   => $eqName,
        'serial' => $invNumber,
        'cost'   => $eqCost . ' руб.',
    ];
}
$stmt2->close();

$phpWord = new PhpWord();
$section = $phpWord->addSection(['marginTop'=>600, 'marginLeft'=>600, 'marginRight'=>600]);

$section->addText('АКТ', ['bold'=>true, 'size'=>14], ['alignment'=>Jc::CENTER]);
$section->addText('приема-передачи оборудования на временное пользование', null, ['alignment'=>Jc::CENTER]);
$section->addTextBreak(1);

$city = 'Пермь';
$now = new DateTime();
$day = $now->format('d');
$monthNames = ['января','февраля','марта','апреля','мая','июня','июля','августа','сентября','октября','ноября','декабря'];
$month = $monthNames[(int)$now->format('n') - 1];
$year = $now->format('Y');

$dateTable = $section->addTable();
$dateTable->addRow();
$dateTable->addCell(4500)->addText("г. {$city}", null, ['alignment'=>Jc::START]);
$dateTable->addCell(4500)->addText("{$day} {$month} {$year} г.", null, ['alignment'=>Jc::END]);
$section->addTextBreak(1);

$institution = 'КГАПОУ Пермский Авиационный техникум им. А.Д. Швецова';
$section->addText(
    "{$institution} в целях обеспечения необходимым оборудованием для исполнения должностных обязанностей
" .
    "передаёт сотруднику {$fio}, а сотрудник принимает от учебного учреждения следующее оборудование:",
    null,
    ['alignment'=>Jc::BOTH]
);
$section->addTextBreak(1);

$tableStyle = ['borderSize'=>6, 'borderColor'=>'999999'];
$phpWord->addTableStyle('EquipTable', $tableStyle);
$table = $section->addTable('EquipTable');
$table->addRow();
$table->addCell(1200)->addText('№', ['bold'=>true]);
$table->addCell(6000)->addText('Описание оборудования', ['bold'=>true]);
$table->addCell(3000)->addText('Серийный номер', ['bold'=>true]);
$table->addCell(2000)->addText('Стоимость', ['bold'=>true]);
foreach ($equipment as $i => $item) {
    $table->addRow();
    $table->addCell(1200)->addText((string)($i+1));
    $table->addCell(6000)->addText($item['name']);
    $table->addCell(3000)->addText($item['serial']);
    $table->addCell(2000)->addText($item['cost']);
}
$section->addTextBreak(1);

$section->addText(
    "По окончанию должностных работ «__» ____________ {$year} года, работник обязуется вернуть полученное оборудование.",
    null,
    ['alignment'=>Jc::BOTH]
);
$section->addTextBreak(2);

$signTable = $section->addTable();
$signTable->addRow();
$signTable->addCell(8000)->addText($fio);
$signTable->addCell(4000)->addText('__________________________');
$signTable->addCell(2000)->addText(' __________ 20__ г.');

$fileName = 'act_' . $userId . '_' . date('Ymd') . '.docx';
header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');

$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save('php://output');

Connection::close($db);
exit;
?>