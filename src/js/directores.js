function fecharModal() {
    document.getElementById('fundo-modal').style.display = 'none';
}

function fecharSucesso() {
    document.getElementById('fundo-sucesso').style.display = 'none';
}

function mostrarSucesso(codigo, senha) {
    document.getElementById('sucesso-codigo').textContent = codigo;
    document.getElementById('sucesso-senha').textContent  = senha;
    document.getElementById('fundo-sucesso').style.display = 'flex';
}

function mostrarNotificacao(texto, cor) {
    const notif = document.getElementById('notificacao');
    if (!notif) return;
    notif.textContent = texto;
    notif.style.background = cor;
    notif.classList.add('visivel');
    setTimeout(() => notif.classList.remove('visivel'), 3500);
}

function revisarDirector() {
    const nome   = document.getElementById('nome').value.trim();
    const bi     = document.getElementById('bi').value.trim();
    const email  = document.getElementById('email').value.trim();
    const deptEl = document.getElementById('id_departamento');
    const nivel  = document.querySelector('input[name="nivel"]:checked')?.value || 'departamento';

    if (!nome || !bi || !email || !deptEl.value) {
        mostrarNotificacao('Preencha todos os campos obrigatórios.', '#c62828');
        return;
    }

    const biRegex = /^[0-9]{12}[A-Z]{1}$/;
    if (!biRegex.test(bi)) {
        mostrarNotificacao('BI inválido. Formato: 12 dígitos + 1 letra maiúscula.', '#c62828');
        return;
    }

    const deptNome  = deptEl.options[deptEl.selectedIndex].text;
    const nivelNome = nivel === 'geral' ? 'Diretor Geral' : 'Dir. Departamento';

    document.getElementById('dadosResumo').innerHTML = `
        <div class="resumo-dir">
            <div class="linha-resumo-dir"><span>Nome</span><strong>${nome}</strong></div>
            <div class="linha-resumo-dir"><span>BI</span><strong>${bi}</strong></div>
            <div class="linha-resumo-dir"><span>E-mail</span><strong>${email}</strong></div>
            <div class="linha-resumo-dir"><span>Departamento</span><strong>${deptNome}</strong></div>
            <div class="linha-resumo-dir"><span>Nível</span><strong>${nivelNome}</strong></div>
        </div>`;

    document.getElementById('fundo-modal').style.display = 'flex';
}

function enviarDirector() {
    fecharModal();
    const form     = document.getElementById('formDirector');
    const formData = new FormData(form);
    formData.append('acao', 'cadastrar');

    fetch('../acoes_php_BD/directores_logica.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.sucesso) {
                mostrarSucesso(data.codigo, data.senha_provisoria);
                form.reset();
                carregarListaDiretores();
            } else {
                mostrarNotificacao('Erro: ' + (data.erro || 'Falha ao guardar'), '#c62828');
            }
        })
        .catch(() => mostrarNotificacao('Erro de comunicação com o servidor.', '#c62828'));
}

function carregarListaDiretores() {
    fetch('../acoes_php_BD/listar_directores.php')
        .then(r => r.json())
        .then(data => {
            const corpo = document.getElementById('listaDiretoresCorpo');
            if (!data.sucesso || !data.dados.length) {
                corpo.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-4">Nenhum diretor registado.</td></tr>';
                return;
            }
            corpo.innerHTML = data.dados.map(dir => `
                <tr>
                    <td>
                        <i class="bi bi-person-badge me-2 text-muted"></i>
                        <strong>${dir.nome}</strong>
                    </td>
                    <td>
                        <span class="badge-nivel-${dir.nivel === 'geral' ? 'geral' : 'dept'}">
                            ${dir.nivel === 'geral' ? 'Geral' : 'Departamento'}
                        </span>
                    </td>
                    <td><span class="badge-depto">${dir.nome_departamento || '—'}</span></td>
                    <td><code>${dir.codigo_acesso}</code></td>
                </tr>`).join('');
        });
}

document.addEventListener('DOMContentLoaded', carregarListaDiretores);
