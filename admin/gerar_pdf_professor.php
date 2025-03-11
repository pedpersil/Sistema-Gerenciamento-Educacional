<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../dompdf/vendor/autoload.php';
require_once __DIR__ . '/../classes/Professor.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Verifique se o ID foi passado e se ele é um número válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID inválido.");
}

$id = $_GET['id'];
$professor = new Professor();
$info = $professor->getAllInfoTeacherById($id);

if (!$info) {
    die("Professor não encontrado.");
}

// Configuração do DOMPdf
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$dompdf = new Dompdf($options);

// Construção do HTML para o PDF
$html = '<h2 style="text-align: center;">Detalhes do Docente</h2>';
$html .= '<table border="1" width="100%" cellspacing="0" cellpadding="5">';
$html .= "<tr><th>ID</th><td>{$info['id']}</td></tr>";
$html .= "<tr><th>Nome</th><td>" . htmlspecialchars($info['name']) . "</td></tr>";
$html .= "<tr><th>Email</th><td>" . htmlspecialchars($info['email']) . "</td></tr>";
$html .= "<tr><th>Telefone</th><td>" . htmlspecialchars($info['telephone']) . "</td></tr>";
$html .= "<tr><th>Identidade</th><td>" . htmlspecialchars($info['identity']) . "</td></tr>";
$html .= "<tr><th>CPF</th><td>" . htmlspecialchars($info['cpf']) . "</td></tr>";
$html .= "<tr><th>Data de Criação</th><td>" . date("d/m/Y H:i", strtotime($info['created_at'])) . "</td></tr>";
$html .= "<tr><th>Cursos Vinculados</th><td>" . htmlspecialchars($info['courses'] ?? 'Nenhum curso vinculado') . "</td></tr>";

$html .= "<tr><th>Disciplinas Ministradas</th><td>";
if (!empty($info['disciplines'])) {
    $html .= '<table border="1" width="100%" cellspacing="0" cellpadding="5">';
    $html .= '<thead><tr><th>Disciplina</th><th>Código</th><th>Curso</th></tr></thead><tbody>';
    foreach ($info['disciplines'] as $disciplina) {
        $html .= "<tr>
                    <td>" . htmlspecialchars($disciplina['name']) . "</td>
                    <td>" . htmlspecialchars($disciplina['code']) . "</td>
                    <td>" . htmlspecialchars($disciplina['course_name']) . "</td>
                  </tr>";
    }
    $html .= '</tbody></table>';
} else {
    $html .= '<p>Nenhuma disciplina ministrada.</p>';
}
$html .= "</td></tr></table>";

// Carrega o HTML no DOMPdf
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Envia o PDF para o navegador
$dompdf->stream("professor_{$info['id']}.pdf", ["Attachment" => false]);
?>
