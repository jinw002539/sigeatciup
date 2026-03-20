function revisarDados() {
    const nome  = document.getElementById('nome').value.trim();
    const bi    = document.getElementById('bi').value.trim();
    const depto = document.getElementById('departamento').value;
    const cargo = document.getElementById('cargo').value.trim();
    const biRegex = /^[0-9]{12}[A-Z]{1}$/;

    if (!biRegex.test(bi)) {
        mostrarNotificacao('BI inválido. Formato: 12 dígitos + 1 letra maiúscula.', '#c62828');
        return;
    }
    if (!nome || !cargo) {
        mostrarNotificacao('Preencha todos os campos obrigatórios.', '#c62828');
        return;
    }

    document.getElementById('dadosResumo').innerHTML = `
        <div class="resumo">
            <p><strong>Nome:</strong> ${nome}</p>
            <p><strong>BI:</strong> ${bi}</p>
            <p><strong>Departamento:</strong> ${depto}</p>
            <p><strong>Cargo:</strong> ${cargo}</p>
            <p class="nota">O código de acesso será gerado automaticamente.</p>
        </div>`;

    document.getElementById('fundo-modal').style.display = 'flex';
}

function fecharModal() {
    document.getElementById('fundo-modal').style.display = 'none';
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
            if (!corpo) return;
            if (!res.sucesso || !res.dados.length) {
                corpo.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Nenhum funcionário registado.</td></tr>';
                return;
            }
            corpo.innerHTML = res.dados.map(f => `
                <tr>
                    <td><i class="bi bi-person-circle me-2 text-muted"></i>${f.nome}</td>
                    <td>${f.bi}</td>
                    <td><span class="badge-depto">${f.departamento}</span></td>
                    <td>${f.cargo}</td>
                    <td><code>${f.codigo_acesso}</code></td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-warning me-1" onclick="prepararEdicao('${f.bi}')">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="eliminar('${f.bi}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>`).join('');
        })
        .catch(() => mostrarNotificacao('Erro ao carregar dados.', '#c62828'));
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

    fetch('../acoes_php_BD/funcionario_logica.php', { method: 'POST', body: formData })
        .then(r => r.json())
        .then(data => {
            if (data.sucesso) {
                mostrarNotificacao(`Cadastrado com sucesso! Código: ${data.codigo}`, '#004d40');
                form.reset();
            } else {
                mostrarNotificacao('Erro ao guardar. Tente novamente.', '#c62828');
            }
        })
        .catch(() => mostrarNotificacao('Erro de comunicação.', '#c62828'));
}

function alternarSessao(tipo) {
    const cadastro = document.getElementById('sessao-cadastro');
    const lista    = document.getElementById('sessao-lista');
    if (tipo === 'lista') {
        cadastro.style.display = 'none';
        lista.style.display    = 'block';
        carregarTabela();
    } else {
        cadastro.style.display = 'block';
        lista.style.display    = 'none';
    }
}
