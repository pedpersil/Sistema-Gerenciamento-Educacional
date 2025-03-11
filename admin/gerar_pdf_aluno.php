<?php
require_once __DIR__ . '/../dompdf/vendor/autoload.php';
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../classes/Aluno.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Verifique se o ID foi passado e se é um número válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido.");
}

$id = $_GET['id'];
$aluno = new Aluno();
$info = $aluno->getAllInfoStudentById($id);

if (!$info) {
    die("Discente não encontrado.");
}

// Configuração do DOMPdf
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

// Construção do HTML para o PDF
$html = '<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Discente</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; margin: 20px; }
        h2 { text-align: center; }
        table { width: 80%; margin: auto; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .sub-table { width: 100%; margin-top: 10px; }
        .sub-table th { background-color: #f9f9f9; }
    </style>
</head>
<body>

<h2>Detalhes do Discente</h2>

<table>
    <tbody>
        <tr><th>ID</th><td>' . $info['student_id'] . '</td></tr>
        <tr><th>Nome</th><td>' . htmlspecialchars($info['student_name']) . '</td></tr>
        <tr><th>Email</th><td>' . htmlspecialchars($info['email']) . '</td></tr>
        <tr><th>Gênero</th><td>' . htmlspecialchars($info['gender']) . '</td></tr>
        <tr><th>Identidade</th><td>' . htmlspecialchars($info['identity']) . '</td></tr>
        <tr><th>CPF</th><td>' . htmlspecialchars($info['cpf']) . '</td></tr>
        <tr><th>Nacionalidade</th><td>' . htmlspecialchars($info['nationality']) . '</td></tr>
        <tr><th>Naturalidade</th><td>' . htmlspecialchars($info['birthplace']) . '</td></tr>
        <tr><th>Data de Nascimento</th><td>' . date("d/m/Y", strtotime($info['birth_date'])) . '</td></tr>
        <tr><th>Nome do Pai</th><td>' . htmlspecialchars($info['father_name']) . '</td></tr>
        <tr><th>Nome da Mãe</th><td>' . htmlspecialchars($info['mother_name']) . '</td></tr>
        <tr><th>Número da Matrícula</th><td>' . htmlspecialchars($info['enrollment_number']) . '</td></tr>
        <tr><th>Status</th><td>' . htmlspecialchars($info['student_status']) . '</td></tr>
        <tr><th>Curso</th><td>' . ($info['course_name'] ? htmlspecialchars($info['course_name']) : '<p class="text-muted">Nenhum curso matriculado.</p>') . '</td></tr>
    </tbody>
</table>';

if (!empty($info['disciplines'])) {
    $html .= '<h2>Disciplinas, Notas e Média</h2>
    <table class="sub-table">
        <thead>
            <tr>
                <th>Disciplina</th>
                <th>Código</th>
                <th>Nota 1</th>
                <th>Nota 2</th>
                <th>Nota 3</th>
                <th>Nota 4</th>
                <th>Média</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($info['grades'] as $discipline) {
        $nota1 = isset($discipline['grade1']) ? $discipline['grade1'] : 'S/N';
        $nota2 = isset($discipline['grade2']) ? $discipline['grade2'] : 'S/N';
        $nota3 = isset($discipline['grade3']) ? $discipline['grade3'] : 'S/N';
        $nota4 = isset($discipline['grade4']) ? $discipline['grade4'] : 'S/N';

        // Calcula a média se todas as notas forem numéricas
        $media = (is_numeric($nota1) && is_numeric($nota2) && is_numeric($nota3) && is_numeric($nota4))
            ? number_format(($nota1 + $nota2 + $nota3 + $nota4) / 4, 2)
            : 'S/N';

        $html .= '<tr>
            <td>' . htmlspecialchars($discipline['discipline_name']) . '</td>
            <td>' . htmlspecialchars($discipline['discipline_code']) . '</td>
            <td>' . $nota1 . '</td>
            <td>' . $nota2 . '</td>
            <td>' . $nota3 . '</td>
            <td>' . $nota4 . '</td>
            <td>' . $media . '</td>
        </tr>';
    }

    $html .= '</tbody></table>';
} else {
    $html .= '<p class="text-muted" style="text-align: center;">Nenhuma disciplina matriculada.</p>';
}

$html .= '</body></html>';

// Carrega o HTML no DOMPdf
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Envia o PDF para o navegador
$dompdf->stream("aluno_{$info['student_id']}.pdf", ["Attachment" => false]);
?>
