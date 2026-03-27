function revisarDados() {
    const nome        = document.getElementById('nome').value.trim();
    const bi          = document.getElementById('bi').value.trim();
    const email       = document.getElementById('email').value.trim();
    const deptoSelect = document.getElementById('id_departamento');
    const nivelSelect = document.getElementById('nivel_acesso');
    const cargoEl     = document.getElementById('cargo');

    if (!nome || !bi || !email || !deptoSelect || !deptoSelect.value) {
        mostrarNotificacao('Preencha todos os campos obrigatórios.', '#c62828');
        return;
    }

    const biRegex = /^[0-9]{12}[A-Z]{1}$/;
    if (!biRegex.test(bi)) {
        mostrarNotificacao('BI inválido. Formato: 12 dígitos + 1 letra maiúscula.', '#c62828');
        return;
    }

    const deptoNome = deptoSelect.options[deptoSelect.selectedIndex].text;
    const nivelNome = nivelSelect.options[nivelSelect.selectedIndex].text;
    const cargoVal  = cargoEl && cargoEl.type !== 'hidden' ? cargoEl.value.trim() : null;

    document.getElementById('dadosResumo').innerHTML = `
        <div class="resumo-dados-func">
            <div class="linha-resumo-func"><span>Nome</span><strong>${nome}</strong></div>
            <div class="linha-resumo-func"><span>BI</span><strong>${bi}</strong></div>
            <div class="linha-resumo-func"><span>E-mail</span><strong>${email}</strong></div>
            <div class="linha-resumo-func"><span>Departamento</span><strong>${deptoNome}</strong></div>
            ${cargoVal ? `<div class="linha-resumo-func"><span>Cargo</span><strong>${cargoVal}</strong></div>` : ''}
            <div class="linha-resumo-func"><span>Nível</span><strong>${nivelNome}</strong></div>
            <p class="nota-resumo-func"><i class="bi bi-envelope me-1"></i>Link de ativação será enviado para este e-mail.</p>
        </div>`;

    document.getElementById('fundo-modal').style.display = 'flex';
}

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
    setTimeout(() => notif.classList.remove('visivel'), 4000);
}

function carregarTabela() {
    fetch('../acoes_php_BD/listar_funcionarios.php')
        .then(r => r.json())
        .then(res => {
            const corpo = document.getElementById('tabelaCorpo');
            if (!res.sucesso || !res.dados.length) {
                corpo.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Nenhum funcionário registado.</td></tr>';
                return;
            }
            corpo.innerHTML = res.dados.map(f => `
                <tr>
                    <td><i class="bi bi-person-circle me-2 text-muted"></i><strong>${f.nome}</strong></td>
                    <td><small>${f.bi}</small></td>
                    <td><span class="badge-depto">${f.departamento || '—'}</span></td>
                    <td><span class="badge-nivel">${f.nivel_acesso || '—'}</span></td>
                    <td><code>${f.codigo_acesso}</code></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-outline-danger" onclick="eliminar('${f.bi}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>`).join('');
        });
}

function eliminar(bi) {
    if (!confirm('Deseja remover este funcionário?')) return;
    fetch(`../acoes_php_BD/funcionario_logica.php?eliminar=${bi}`)
        .then(r => r.json())
        .then(res => {
            mostrarNotificacao(res.sucesso ? 'Funcionário removido.' : 'Erro ao remover.', res.sucesso ? '#004d40' : '#c62828');
            if (res.sucesso) carregarTabela();
        });
}

function enviarParaBD() {
    fecharModal();
    const form     = document.getElementById('formCadastro');
    const formData = new FormData(form);
    formData.append('acao', 'cadastrar');

    fetch('../acoes_php_BD/funcionario_logica.php', { method: 'POST', body: formData })
        .then(r => r.text())
        .then(text => {
            try {
                const data = JSON.parse(text);
                if (data.sucesso) {
                    mostrarSucesso(data.codigo, data.senha_provisoria);
                    form.reset();
                    if (typeof carregarTabela === 'function') carregarTabela();
                } else {
                    mostrarNotificacao('Erro: ' + (data.erro || 'Falha ao guardar'), '#c62828');
                }
            } catch (e) {
                mostrarNotificacao('Erro crítico no servidor.', '#c62828');
            }
        })
        .catch(() => mostrarNotificacao('Erro de comunicação.', '#c62828'));
}

function alternarSessao(tipo) {
    const cadastro = document.getElementById('sessao-cadastro');
    const lista    = document.getElementById('sessao-lista');
    const btnCad   = document.getElementById('btn-cadastro');
    const btnLista = document.getElementById('btn-lista');

    if (tipo === 'lista') {
        cadastro.style.display = 'none';
        lista.style.display    = 'block';
        btnCad.classList.remove('ativa');
        btnLista.classList.add('ativa');
        carregarTabela();
    } else {
        cadastro.style.display = 'block';
        lista.style.display    = 'none';
        btnCad.classList.add('ativa');
        btnLista.classList.remove('ativa');
    }
}
