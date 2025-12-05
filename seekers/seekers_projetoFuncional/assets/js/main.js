// Validação de formulários
function validarFormulario(formId) {
    const form = document.getElementById(formId);
    const inputs = form.querySelectorAll('input[required], textarea[required]');
    let valido = true;

    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.classList.add('is-invalid');
            valido = false;
        } else {
            input.classList.remove('is-invalid');
        }
    });

    return valido;
}

// AJAX para curtir/descurtir builds
function curtirBuild(buildId) {
    $.ajax({
        url: 'ajax/toggle_curtida.php',
        method: 'POST',
        data: { build_id: buildId },
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                $('#curtidas-' + buildId).text(response.curtidas);
                const btn = $('#btn-curtir-' + buildId);
                if(response.action === 'added') {
                    btn.removeClass('btn-outline-primary').addClass('btn-success');
                    btn.html('❤️ ' + response.curtidas + ' Curtido');
                    btn.prop('disabled', false);
                } else {
                    btn.removeClass('btn-success').addClass('btn-outline-primary');
                    btn.html('❤️ <span id="curtidas-' + buildId + '">' + response.curtidas + '</span>');
                }
            } else {
                alert(response.message);
            }
        },
        error: function() {
            alert('Erro ao processar curtida');
        }
    });
}

// Busca dinâmica de builds
function buscarBuilds() {
    const termo = $('#busca-builds').val();
    const jogo = $('#filtro-jogo').val();
    
    $.ajax({
        url: 'ajax/buscar_builds.php',
        method: 'GET',
        data: { termo: termo, jogo: jogo },
        success: function(response) {
            $('#lista-builds').html(response);
        }
    });
}

// Validação de email
function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// Validação de senha
function validarSenha(senha) {
    return senha.length >= 6;
}

// Confirmar exclusão
function confirmarExclusao(item) {
    return confirm(`Tem certeza que deseja excluir ${item}?`);
}

$(document).ready(function() {
    // Validação em tempo real
    $('input[type="email"]').on('blur', function() {
        if (!validarEmail($(this).val())) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });

    $('input[type="password"]').on('blur', function() {
        if (!validarSenha($(this).val())) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
});