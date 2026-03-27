function carregarTarefas() {
    const periodo = document.getElementById('filtro-periodo').value;
    const corpo   = document.getElementById('tabelaTarefasCorpo');
    corpo.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted"><span class="spinner-border spinner-border-sm me-2"></span>A carregar...</td></tr>';

    fetch(`../acoes_php_BD/tarefa_logica.php?listar=true&periodo=${periodo}`)
        .then(r => r.json())
        .then(res => {
            if (!res.sucesso || !res.tarefas || !res.tarefas.length) {
                corpo.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Nenhuma tarefa encontrada.</td></tr>';
                return;
            }
            corpo.innerHTML = res.tarefas.map(t => {
                const bloqueada  = t.bloqueada == '1' || t.bloqueada === true;
                const autorizada = t.autorizada_por_direcao == '1' || t.autorizada_por_direcao === true;
                const classeLinh = bloqueada ? 'linha-bloqueada' : '';
                const iconeBloq  = bloqueada ? '<i class="bi bi-lock-fill text-danger me-1" title="Bloqueada — prazo expirado"></i>' : '';

                let acoes = '';

                if (EHAMIN) {
                    if (!autorizada && !bloqueada) {
                        acoes += `<button class="btn btn-sm btn-success me-1" title="Autorizar" onclick="autorizarTarefa(${t.id})"><i class="bi bi-check2-circle"></i></button>`;
                    }
                    if (!bloqueada) {
                        acoes += `<button class="btn btn-sm btn-warning me-1" title="Editar" onclick="abrirModalEditar(${t.id},'${escapar(t.actividade)}','${escapar(t.objectivos)}','${escapar(t.resultado_esperado)}','${t.prazo_execucao}','${t.estado}','${t.id_departamento}')"><i class="bi bi-pencil"></i></button>`;
                    }
                    acoes += `<button class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarTarefa(${t.id})"><i class="bi bi-trash"></i></button>`;
                } else {
                    if (autorizada && !bloqueada && !t.id_funcionario) {
                        acoes += `<button class="btn btn-sm btn-primary me-1" title="Atribuir" onclick="abrirAtribuir(${t.id})"><i class="bi bi-person-plus"></i></button>`;
                    }
                    if (!bloqueada) {
                        acoes += `<button class="btn btn-sm btn-warning me-1" title="Editar" onclick="abrirModalEditar(${t.id},'${escapar(t.actividade)}','${escapar(t.objectivos)}','${escapar(t.resultado_esperado)}','${t.prazo_execucao}','${t.estado}','')"><i class="bi bi-pencil"></i></button>`;
                    }
                }

                const badgeAuth = !autorizada
                    ? '<span class="badge bg-warning text-dark ms-1">Pendente</span>'
                    : '<span class="badge bg-success ms-1">Autorizada</span>';

                return `<tr class="${classeLinh}">
                    <td>${iconeBloq}<strong>${t.actividade}</strong>${EHAMIN ? badgeAuth : ''}</td>
                    <td><span class="badge-depto">${t.nome_departamento || '—'}</span></td>
                    <td>${t.id_funcionario ? '<i class="bi bi-person-check me-1 text-success"></i>Atribuído' : '<span class="text-muted small">Não atribuído</span>'}</td>
                    <td>${formatarData(t.prazo_execucao)}</td>
                    <td>${badgeEstado(t.estado)}</td>
                    <td class="text-center">${acoes}</td>
                </tr>`;
            }).join('');
        })
        .catch(() => {
            document.getElementById('tabelaTarefasCorpo').innerHTML =
                '<tr><td colspan="6" class="text-center text-danger py-4">Erro ao carregar tarefas.</td></tr>';
        });
}

function guardarTarefa() {
    const id      = document.getElementById('tarefa-id').value;
    const activ   = document.getElementById('tarefa-actividade').value.trim();
    const prazo   = document.getElementById('tarefa-prazo').value;
    const deptEl  = document.getElementById('tarefa-dept');

    if (!activ || !prazo) {
        alert('Preencha a actividade e o prazo.');
        return;
    }

    // FormData para que o PHP leia via $_POST
    const fd = new FormData();
    if (id) fd.append('id', id);
    fd.append('actividade',         activ);
    fd.append('objectivos',         document.getElementById('tarefa-objectivos').value.trim());
    fd.append('resultado_esperado', document.getElementById('tarefa-resultado').value.trim());
    fd.append('prazo_execucao',     prazo);
    fd.append('estado',             document.getElementById('tarefa-estado').value);
    if (deptEl) fd.append('id_departamento', deptEl.value);

    fetch('../acoes_php_BD/tarefa_logica.php', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => {
            if (res.sucesso) {
                fecharModal();
                carregarTarefas();
            } else {
                alert('Erro ao guardar: ' + (res.erro || 'Falha no servidor'));
            }
        })
        .catch(err => alert('Erro de comunicação: ' + err));
}

function autorizarTarefa(id) {
    if (!confirm('Deseja autorizar esta actividade para execução oficial?')) return;

    const fd = new FormData();
    fd.append('id', id);

    fetch('../acoes_php_BD/tarefa_logica.php?autorizar=true', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.sucesso) {
                carregarTarefas();
            } else {
                alert('Erro ao autorizar: ' + (data.erro || 'Falha no servidor'));
            }
        })
        .catch(err => alert('Erro de comunicação: ' + err));
}

function eliminarTarefa(id) {
    if (!confirm('Eliminar esta tarefa permanentemente?')) return;

    const fd = new FormData();
    fd.append('id', id);

    fetch('../acoes_php_BD/tarefa_logica.php?eliminar=true', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(res => { if (res.sucesso) carregarTarefas(); });
}

function abrirAtribuir(idTarefa) {
    document.getElementById('atribuir-tarefa-id').value = idTarefa;
    const sel = document.getElementById('select-funcionario');
    sel.innerHTML = '<option>A carregar...</option>';
    document.getElementById('fundo-atribuir').classList.add('aberto');

    fetch(`../acoes_php_BD/tarefa_logica.php?funcionarios_dept=true&dept=${encodeURIComponent(DEPT)}`)
        .then(r => r.json())
        .then(res => {
            sel.innerHTML = (res.sucesso && res.dados && res.dados.length)
                ? res.dados.map(f => `<option value="${f.id}">${f.nome} — ${f.cargo}</option>`).join('')
                : '<option>Nenhum funcionário disponível</option>';
        });
}

function confirmarAtribuicao() {
    const idTarefa = document.getElementById('atribuir-tarefa-id').value;
    const idFunc   = document.getElementById('select-funcionario').value;
    if (!idFunc) return;

    fetch(`../acoes_php_BD/tarefa_logica.php?atribuir=${idTarefa}&funcionario=${idFunc}`)
        .then(r => r.json())
        .then(res => { if (res.sucesso) { fecharAtribuir(); carregarTarefas(); } });
}

function fecharAtribuir() {
    document.getElementById('fundo-atribuir').classList.remove('aberto');
}

function abrirModal() {
    document.getElementById('modal-titulo').innerHTML = '<i class="bi bi-plus-circle me-2"></i>Nova Tarefa';
    document.getElementById('tarefa-id').value         = '';
    document.getElementById('tarefa-actividade').value = '';
    document.getElementById('tarefa-objectivos').value = '';
    document.getElementById('tarefa-resultado').value  = '';
    document.getElementById('tarefa-prazo').value      = '';
    document.getElementById('tarefa-estado').value     = 'Por atribuir';
    const deptEl = document.getElementById('tarefa-dept');
    if (deptEl) deptEl.value = '';
    document.getElementById('fundo-modal').classList.add('aberto');
}

function abrirModalEditar(id, actividade, objectivos, resultado, prazo, estado, dept) {
    document.getElementById('modal-titulo').innerHTML  = '<i class="bi bi-pencil me-2"></i>Editar Tarefa';
    document.getElementById('tarefa-id').value         = id;
    document.getElementById('tarefa-actividade').value = actividade;
    document.getElementById('tarefa-objectivos').value = objectivos;
    document.getElementById('tarefa-resultado').value  = resultado;
    document.getElementById('tarefa-prazo').value      = prazo ? prazo.substring(0, 10) : '';
    document.getElementById('tarefa-estado').value     = estado;
    const deptEl = document.getElementById('tarefa-dept');
    if (deptEl && dept) deptEl.value = dept;
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
        'Pendente':             'bg-secondary',
        'Pendente de Aprovação':'bg-warning text-dark',
        'Aguardando':           'bg-info text-dark',
        'Autorizada':           'bg-info text-dark',
        'Em curso':             'bg-primary',
        'Concluída':            'bg-success',
        'Cancelada':            'bg-danger',
    };
    return `<span class="badge ${mapa[estado] || 'bg-secondary'}">${estado}</span>`;
}

function escapar(str) {
    return (str || '').replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
}
