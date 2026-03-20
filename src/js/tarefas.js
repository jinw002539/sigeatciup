function carregarTarefas() {
    const periodo = document.getElementById('filtro-periodo').value;
    const corpo   = document.getElementById('tabelaTarefasCorpo');
    corpo.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted"><span class="spinner-border spinner-border-sm me-2"></span>A carregar...</td></tr>';

    fetch(`../acoes_php_BD/tarefa_logica.php?listar=true&periodo=${periodo}`)
        .then(r => r.json())
        .then(res => {
            if (!res.sucesso || !res.dados.length) {
                corpo.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Nenhuma tarefa encontrada.</td></tr>';
                return;
            }
            corpo.innerHTML = res.dados.map(t => `
                <tr>
                    <td><strong>${t.actividade}</strong></td>
                    <td><small class="text-muted">${t.objectivos || '—'}</small></td>
                    <td><small class="text-muted">${t.resultado_esperado || '—'}</small></td>
                    <td>${formatarData(t.prazo_execucao)}</td>
                    <td>${badgeEstado(t.estado)}</td>
                    <td class="text-center">
                        <button class="btn btn-sm btn-warning me-1" title="Editar"
                            onclick="abrirModalEditar(${t.id}, '${escapar(t.actividade)}', '${escapar(t.objectivos)}', '${escapar(t.resultado_esperado)}', '${t.prazo_execucao}', '${t.estado}')">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <button class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarTarefa(${t.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>`).join('');
        })
        .catch(() => {
            document.getElementById('tabelaTarefasCorpo').innerHTML =
                '<tr><td colspan="6" class="text-center text-danger py-4">Erro ao carregar tarefas.</td></tr>';
        });
}

function guardarTarefa() {
    const id   = document.getElementById('tarefa-id').value;
    const body = {
        id:                 id || null,
        actividade:         document.getElementById('tarefa-actividade').value.trim(),
        objectivos:         document.getElementById('tarefa-objectivos').value.trim(),
        resultado_esperado: document.getElementById('tarefa-resultado').value.trim(),
        prazo_execucao:     document.getElementById('tarefa-prazo').value,
        estado:             document.getElementById('tarefa-estado').value,
    };

    if (!body.actividade || !body.prazo_execucao) return;

    fetch('../acoes_php_BD/tarefa_logica.php', {
        method:  'POST',
        headers: { 'Content-Type': 'application/json' },
        body:    JSON.stringify(body),
    })
        .then(r => r.json())
        .then(res => {
            if (res.sucesso) { fecharModal(); carregarTarefas(); }
        });
}

function eliminarTarefa(id) {
    if (!confirm('Deseja eliminar esta tarefa?')) return;
    fetch(`../acoes_php_BD/tarefa_logica.php?eliminar=${id}`)
        .then(r => r.json())
        .then(res => { if (res.sucesso) carregarTarefas(); });
}

function abrirModal() {
    document.getElementById('modal-titulo').innerHTML       = '<i class="bi bi-plus-circle me-2"></i>Nova Tarefa';
    document.getElementById('tarefa-id').value              = '';
    document.getElementById('tarefa-actividade').value      = '';
    document.getElementById('tarefa-objectivos').value      = '';
    document.getElementById('tarefa-resultado').value       = '';
    document.getElementById('tarefa-prazo').value           = '';
    document.getElementById('tarefa-estado').value          = 'Por atribuir';
    document.getElementById('fundo-modal').classList.add('aberto');
}

function abrirModalEditar(id, actividade, objectivos, resultado, prazo, estado) {
    document.getElementById('modal-titulo').innerHTML       = '<i class="bi bi-pencil me-2"></i>Editar Tarefa';
    document.getElementById('tarefa-id').value              = id;
    document.getElementById('tarefa-actividade').value      = actividade;
    document.getElementById('tarefa-objectivos').value      = objectivos;
    document.getElementById('tarefa-resultado').value       = resultado;
    document.getElementById('tarefa-prazo').value           = prazo.substring(0, 10);
    document.getElementById('tarefa-estado').value          = estado;
    document.getElementById('fundo-modal').classList.add('aberto');
}

function fecharModal() {
    document.getElementById('fundo-modal').classList.remove('aberto');
}

function formatarData(data) {
    if (!data) return '—';
    return new Date(data).toLocaleDateString('pt-PT', { day: '2-digit', month: 'short', year: 'numeric' });
}

function badgeEstado(estado) {
    const mapa = {
        'Por atribuir': 'bg-secondary',
        'Em curso':     'bg-warning text-dark',
        'Concluída':    'bg-success',
        'Cancelada':    'bg-danger',
    };
    return `<span class="badge ${mapa[estado] || 'bg-secondary'}">${estado}</span>`;
}

function escapar(str) {
    return (str || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
}
