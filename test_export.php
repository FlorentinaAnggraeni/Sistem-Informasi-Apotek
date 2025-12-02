<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Test Export');
$sheet->setCellValue('A2', 'Library works!');

$writer = new Xlsx($spreadsheet);
$writer->save('test_export.xlsx');

echo "Test export file created successfully at: " . getcwd() . "/test_export.xlsx\n";
?>
