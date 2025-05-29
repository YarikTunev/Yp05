<?php
require 'connection.php';
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;
?>
<!doctype html>
<html>
<head><link rel="stylesheet" href="styles/pages.css"></head>
<body>
  <h1>Импорт оборудования из XLS</h1>
  <form method="post" enctype="multipart/form-data">
    <input type="file" name="xlsfile" accept=".xls" required>
    <button type="submit">Загрузить и импортировать</button>
  </form>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['xlsfile'])) {
    $tmp = $_FILES['xlsfile']['tmp_name'];
    try {
        $spreadsheet = IOFactory::load($tmp);
        $sheet       = $spreadsheet->getActiveSheet();
        $rows        = $sheet->toArray();
        $header = array_shift($rows);
        $sql = "INSERT INTO equipment (name, serial, model, location) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $imported = 0;
        foreach ($rows as $r) {
            if (trim($r[0]) === '') continue;
            $stmt->execute([$r[0], $r[1], $r[2], $r[3]]);
            $imported++;
        }
        echo "<p>Импортировано записей: {$imported}</p>";
    } catch (\Exception $e) {
        echo "<p style='color:red;'>Ошибка при разборе XLS: ".$e->getMessage()."</p>";
    }
}
?>
</body>
</html>
