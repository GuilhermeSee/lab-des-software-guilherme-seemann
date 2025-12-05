    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="text-warning">SEEKERS</h5>
                    <p>Plataforma de conexão para jogadores de jogos souls-like</p>
                </div>
                <div class="col-md-6">
                    <h6>Links Úteis</h6>
                    <ul class="list-unstyled">
                        <li><a href="contato.php" class="text-light">Fale Conosco</a></li>
                        <li><a href="builds.php" class="text-light">Builds da Comunidade</a></li>
                        <li><a href="sessoes.php" class="text-light">Sessões Abertas</a></li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <p>&copy; 2025 Seekers - Desenvolvido por Guilherme Seemann</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/main.js"></script>
    <?php if(isset($_SESSION['usuarioLogado'])): ?>
    <script>
    // Atualizar contador de notificações automaticamente
    setInterval(function() {
        $.get('ajax/contar_notificacoes.php', function(data) {
            const badge = $('.navbar .badge');
            if(data.count > 0) {
                if(badge.length) {
                    badge.text(data.count).show();
                } else {
                    $('a[href="notificacoes.php"]').append(' <span class="badge bg-danger">' + data.count + '</span>');
                }
            } else {
                badge.remove();
            }
        }, 'json');
    }, 10000);
    </script>
    <?php endif; ?>
</body>
</html>