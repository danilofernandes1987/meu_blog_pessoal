<?php // app/Views/contact/index.php 
?>

<h2><?php echo isset($contentTitle) ? htmlspecialchars($contentTitle) : 'Contato'; ?></h2>
<p class="lead">Tem alguma pergunta, sugestão ou apenas quer dizer um "olá"? Use o formulário abaixo!</p>
<hr>

<?php
// Exibe a flash message de sucesso ou erro após o envio do formulário
displayFlashMessage('contact_feedback');
?>

<div class="row">
    <div class="col-md-8">
        <form method="POST" action="/contact/send"> <?php // Rota para o método send() 
                                                    ?>
            <?= csrfInput(); ?>
            <div class="honeypot-field" style="display:none;">
                <label for="website_url">Website</label>
                <input type="text" id="website_url" name="website_url" tabindex="-1" autocomplete="off">
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Seu Nome</label>
                <input type="text" class="form-control <?php echo isset($errors['name']) ? 'is-invalid' : ''; ?>" id="name" name="name" value="<?php echo htmlspecialchars($old_input['name'] ?? ''); ?>" required>
                <?php if (isset($errors['name'])): ?>
                    <div class="invalid-feedback"><?php echo htmlspecialchars($errors['name']); ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Seu E-mail</label>
                <input type="email" class="form-control <?php echo isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo htmlspecialchars($old_input['email'] ?? ''); ?>" placeholder="seu@email.com" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="invalid-feedback"><?php echo htmlspecialchars($errors['email']); ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="subject" class="form-label">Assunto</label>
                <input type="text" class="form-control <?php echo isset($errors['subject']) ? 'is-invalid' : ''; ?>" id="subject" name="subject" value="<?php echo htmlspecialchars($old_input['subject'] ?? ''); ?>" required>
                <?php if (isset($errors['subject'])): ?>
                    <div class="invalid-feedback"><?php echo htmlspecialchars($errors['subject']); ?></div>
                <?php endif; ?>
            </div>

            <div class="mb-3">
                <label for="message" class="form-label">Sua Mensagem</label>
                <textarea class="form-control <?php echo isset($errors['message']) ? 'is-invalid' : ''; ?>" id="message" name="message" rows="5" required><?php echo htmlspecialchars($old_input['message'] ?? ''); ?></textarea>
                <?php if (isset($errors['message'])): ?>
                    <div class="invalid-feedback"><?php echo htmlspecialchars($errors['message']); ?></div>
                <?php endif; ?>
            </div>

            <button type="submit" class="btn btn-primary">Enviar Mensagem</button>
        </form>
    </div>
    <div class="col-md-4">
        <h4>Outras formas de contato:</h4>
        <p><strong>E-mail:</strong> <a href="mailto:tec.danfer@gmail.com">tec.danfer@gmail.com</a></p>
        <p><strong>LinkedIn:</strong> <a href="https://www.linkedin.com/in/danilofernandessilva/" target="_blank">danilofernandessilva</a></p>
        <p><strong>GitHub:</strong> <a href="https://github.com/danilofernandes1987" target="_blank">danilofernandes1987</a></p>
    </div>
</div>