<?php
// app/Controllers/ContactController.php
namespace App\Controllers;

use App\Core\BaseController;

// --- Importa as classes do PHPMailer ---
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ContactController extends BaseController
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Exibe o formulário de contato.
     * Acessado via GET /contact
     */
    public function index(): void
    {
        // Prepara dados para a view, incluindo possíveis erros de validação
        // ou dados antigos de um envio anterior que falhou.
        $data = [
            'pageTitle'    => 'Contato',
            'contentTitle' => 'Entre em Contato',
            'errors'       => $_SESSION['errors'] ?? [],
            'old_input'    => $_SESSION['old_input'] ?? []
        ];

        // Limpa as mensagens da sessão após prepará-las para a view
        unset($_SESSION['errors']);
        unset($_SESSION['old_input']);

        // Usa o layout principal do site para renderizar a view do formulário
        $this->view('contact.index', $data, 'layouts.main');
    }

    /**
     * Processa os dados do formulário de contato.
     * Acessado via POST /contact/send
     * (Vamos implementar a lógica aqui no próximo passo)
     */
    public function send(): void
    {
        $this->validatePostRequest();

        // --- 1. Verificação do Honeypot ---
        if (!empty($_POST['website_url'])) {
            // É um bot! Nós podemos simplesmente redirecionar para uma página de sucesso
            // sem realmente enviar o e-mail, para não dar pistas ao bot.
            // Ou podemos apenas sair silenciosamente.
            // Redirecionar para a mesma página com uma mensagem de sucesso genérica é uma boa tática.
            setFlashMessage('contact_feedback', 'Obrigado pela sua mensagem!', 'success');
            header('Location: /contact');
            exit;
        }

        // --- 2. Validação dos Campos Reais ---
        $errors = [];
        $old_input = $_POST;
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name)) $errors['name'] = 'O nome é obrigatório.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Por favor, insira um e-mail válido.';
        if (empty($subject)) $errors['subject'] = 'O assunto é obrigatório.';
        if (empty($message)) $errors['message'] = 'A mensagem é obrigatória.';

        if (!empty($errors)) {
            // Se houver erros, guarde-os na sessão e redirecione de volta para o formulário.
            $_SESSION['errors'] = $errors;
            $_SESSION['old_input'] = $old_input;
            header('Location: /contact');
            exit;
        }

        // --- 3. Envio do E-mail (se a validação passou) ---
        $mailConfig = $this->siteConfig['mail'] ?? [];
        $mail = new PHPMailer(true); // true habilita exceções

        try {
            // Configurações do Servidor
            // $mail->SMTPDebug = \PHPMailer\PHPMailer\SMTP::DEBUG_SERVER; // Descomente para depuração detalhada
            $mail->isSMTP();
            $mail->Host       = $mailConfig['host'];
            $mail->SMTPAuth   = $mailConfig['smtp_auth'];
            $mail->Username   = $mailConfig['username'];
            $mail->Password   = $mailConfig['password'];
            $mail->SMTPSecure = $mailConfig['smtp_secure'];
            $mail->Port       = $mailConfig['port'];
            $mail->CharSet    = 'UTF-8';

            // Remetente e Destinatários
            $mail->setFrom($mailConfig['from_email'], 'Contato - ' . $name); // Quem envia (seu gmail)
            $mail->addAddress($this->siteConfig['contactEmail']); // Quem recebe (seu e-mail principal)
            $mail->addReplyTo($email, $name); // Para quem a resposta vai (quem preencheu o form)

            // Conteúdo
            $mail->isHTML(false); // Define que o e-mail não é HTML, mas texto puro
            $mail->Subject = "Nova Mensagem do Site: " . $subject;
            $mail->Body    = "Você recebeu uma nova mensagem do formulário de contato.\n\n" .
                "Nome: " . $name . "\n" .
                "E-mail: " . $email . "\n" .
                "Mensagem:\n" . $message;
            $mail->AltBody = $mail->Body; // Corpo alternativo para clientes de e-mail que não suportam HTML

            $mail->send();
            setFlashMessage('contact_feedback', 'Sua mensagem foi enviada com sucesso! Obrigado pelo contato.', 'success');
        } catch (Exception $e) {
            // Loga o erro real para você, mas mostra uma mensagem genérica para o usuário
            // error_log("Erro no PHPMailer: {$mail->ErrorInfo}");
            setFlashMessage('contact_feedback', 'Ocorreu um erro ao tentar enviar sua mensagem. Detalhe: ' . $mail->ErrorInfo, 'danger');
        }

        header('Location: /contact');
        exit;
    }
}
