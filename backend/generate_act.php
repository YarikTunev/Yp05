<?php
require_once(__DIR__ . '/../Connection.php'); // Сначала подключаем Connection
require_once(__DIR__ . '/classes/equipment.php');   // Затем Equipment
require_once(__DIR__ . '/../vendor/autoload.php'); // PhpOffice\PhpWord

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Неверный идентификатор оборудования.");
}

$equipmentId = intval($_GET['id']);
$equipment = Equipment::GetById($equipmentId);

if (!$equipment) {
    die("Оборудование не найдено.");
}

$tempUser = $equipment->temp_responsible_user_id ?? "Иванов И.И.";

// Создаем Word-документ
$phpWord = new PhpWord();
$section = $phpWord->addSection();

// Заголовок
$section->addText('АКТ', ['bold' => true, 'align' => 'center']);
$section->addText('приема-передачи оборудования на временное пользование', ['align' => 'center']);
$section->addTextBreak(1);
$section->addText('г. Пермь', [], ['align' => 'left']);
$section->addText(date('d.m.Y'), [], ['align' => 'right']);
$section->addTextBreak(1);

// Основной текст
$section->addText("КГАПОУ Пермский Авиационный техникум им. А.Д. Швецова в целях обеспечения необходимым оборудованием для исполнения должностных обязанностей передает сотруднику $tempUser, а сотрудник принимает от учебного учреждения следующее оборудование:");
$section->addTextBreak(1);

// Информация об оборудовании
$section->addText("Наименование: {$equipment->name}");
$section->addText("Инвентарный номер: {$equipment->inventory_number}");
$section->addText("Модель: {$equipment->model_id}");
$section->addText("Стоимость: {$equipment->cost} руб.");
$section->addTextBreak(1);

// Подписи
$section->addText("___________________\n$tempUser\n(подпись)", [], ['align' => 'center']);

// Отправляем файл
header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
header('Content-Disposition: attachment;filename="act_equipment_'.$equipment->id.'.docx"');
header('Cache-Control: max-age=0');

$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('php://output');
exit;