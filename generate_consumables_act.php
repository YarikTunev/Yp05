<?php
require 'connection.php';
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;

$db = Connection::connect();

// Получаем user_id из GET, или показываем форму выбора пользователя
$userId = isset($_GET['user_id']) ? (int)$_GET['user_id'] : 0;
if (!$userId) {
    $res = $db->query("SELECT id, last_name, first_name, middle_name FROM users");
    echo '<h2>Сформировать акт приёма-передачи расходных материалов</h2>';
    echo '<form method="get">';
    echo '<label for="user_id">Сотрудник:</label> ';
    echo '<select id="user_id" name="user_id">';
    while ($u = $res->fetch_assoc()) {
        $fio = trim("{$u['last_name']} {$u['first_name']} {$u['middle_name']}");
        echo "<option value=\"{$u['id']}\">" . htmlspecialchars($fio) . "</option>";
    }
    echo '</select> ';
    echo '<button type="submit">Сформировать акт</button>';
    echo '</form>';
    exit;
}

// Достаём ФИО выбранного сотрудника
$stmt = $db->prepare('SELECT last_name, first_name, middle_name FROM users WHERE id = ?');
$stmt->bind_param('i', $userId);
$stmt->execute();
$stmt->bind_result($last, $first, $middle);
if (!$stmt->fetch()) {
    die('Сотрудник не найден');
}
$stmt->close();
$fio = trim("$last $first $middle");

// Достаём список расходных материалов переданных сотруднику
$stmt2 = $db->prepare(
    'SELECT name, quantity, cost 
       FROM Consumables 
      WHERE temp_responsible_user_id = ?'
);
$stmt2->bind_param('i', $userId);
$stmt2->execute();
$stmt2->bind_result($name, $qty, $cost);
$items = [];
while ($stmt2->fetch()) {
    $items[] = [
        'name'     => $name,
        'quantity' => $qty,
        'cost'     => number_format($cost, 2, '.', ' ') . ' руб.'
    ];
}
$stmt2->close();

// Генерация Word-документ
$phpWord = new PhpWord();
$section = $phpWord->addSection([
    'marginTop'    => 600,
    'marginLeft'   => 600,
    'marginRight'  => 600,
]);

$section->addText('АКТ', ['bold'=>true, 'size'=>14], ['alignment'=>Jc::CENTER]);
$section->addText(
    'приёма-передачи расходных материалов',
    null,
    ['alignment'=>Jc::CENTER]
);
$section->addTextBreak(1);

$now   = new DateTime();
$day   = $now->format('d');
$monthNames = [
    'января','февраля','марта','апреля','мая','июня',
    'июля','августа','сентября','октября','ноября','декабря'
];
$month = $monthNames[(int)$now->format('n') - 1];
$year  = $now->format('Y');
$table = $section->addTable();
$table->addRow();
$table->addCell(4500)
      ->addText("г. Пермь", null, ['alignment'=>Jc::START]);
$table->addCell(4500)
      ->addText("{$day} {$month} {$year} г.", null, ['alignment'=>Jc::END]);
$section->addTextBreak(1);

$institution = 'КГАПОУ Пермский Авиационный техникум им. А.Д. Швецова';
$section->addText(
    "{$institution} в целях обеспечения необходимыми расходными материалами для исполнения должностных обязанностей\n"
   ."передаёт сотруднику {$fio}, а сотрудник принимает от учебного учреждения следующие расходные материалы:",
    null,
    ['alignment'=>Jc::BOTH]
);
$section->addTextBreak(1);

$phpWord->addTableStyle('ConsTable', [
    'borderSize'=>6, 
    'borderColor'=>'999999'
]);

$tbl = $section->addTable('ConsTable');
$tbl->addRow();
$tbl->addCell(1000)->addText('№', ['bold'=>true]);
$tbl->addCell(5000)->addText('Наименование', ['bold'=>true]);
$tbl->addCell(2000)->addText('Кол-во', ['bold'=>true]);
$tbl->addCell(2000)->addText('Стоимость', ['bold'=>true]);

foreach ($items as $i => $it) {
    $tbl->addRow();
    $tbl->addCell(1000)->addText((string)($i+1));
    $tbl->addCell(5000)->addText($it['name']);
    $tbl->addCell(2000)->addText((string)$it['quantity']);
    $tbl->addCell(2000)->addText($it['cost']);
}
$section->addTextBreak(1);

$section->addText(
    "По окончанию должностных работ «__» ____________ {$year} года, работник обязуется вернуть оставшиеся материалы.",
    null,
    ['alignment'=>Jc::BOTH]
);
$section->addTextBreak(2);

$sign = $section->addTable();
$sign->addRow();
$sign->addCell(6000)->addText($fio);
$sign->addCell(4000)->addText('__________________________');
$sign->addCell(2000)->addText('__________ 20__ г.');

// Выгружаем файл
$fileName = "act_consumables_{$userId}_" . date('Ymd') . ".docx";
header('Content-Description: File Transfer');
header('Content-Disposition: attachment; filename="' . $fileName . '"');
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
$writer = IOFactory::createWriter($phpWord, 'Word2007');
$writer->save('php://output');

Connection::close($db);
exit;
