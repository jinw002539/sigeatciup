function escapar(texto) {
    if (!texto) return '';
    return texto.replace(/\\/g, '\\\\').replace(/'/g, "\\'").replace(/"/g, '&quot;');
}

function carregarTarefas() {
    // const periodo = document.getElementById('filtro-periodo').value;
    const filtro = document.getElementById('filtro-periodo');
    const periodo = filtro ? filtro.value : 'todos';
    // console.log("A carregar período:", periodo);
    const corpo   = document.getElementById('tabelaTarefasCorpo');
    if (!corpo) return;

    // 1. Feedback visual (Spinner)
    corpo.innerHTML = `
        <tr>
            <td colspan="6" class="text-center py-4 text-muted">
                <span class="spinner-border spinner-border-sm me-2"></span>A carregar tarefas...
            </td>
        </tr>`;

    fetch(`../acoes_php_BD/tarefa_logica.php?listar=true&periodo=${periodo}`)
        .then(r => {
            if (!r.ok) throw new Error('Erro na rede');
            return r.json();
        })
        .then(res => {
            // 2. Verificar se o servidor retornou erro de SQL ou Permissão
            if (!res.sucesso) {
                console.error("Erro do Servidor:", res.erro);
                corpo.innerHTML = `<tr><td colspan="6" class="text-center text-danger py-4"><i class="bi bi-bug me-2"></i>Erro: ${res.erro}</td></tr>`;
                return;
            }

            // 3. Normalizar a lista (aceita res.tarefas ou res.dados)
            const lista = res.tarefas || res.dados || [];

            // 4. Se a lista estiver vazia
            if (lista.length === 0) {
                corpo.innerHTML = '<tr><td colspan="6" class="text-center py-4 text-muted">Nenhuma tarefa encontrada para este período.</td></tr>';
                return;
            }

            // 5. Gerar o HTML das linhas
            corpo.innerHTML = lista.map(t => {
                // Tratamento de tipos (PostgreSQL retorna 't'/'f' ou boolean)
                const bloqueada  = (t.bloqueada === true || t.bloqueada === 't' || t.bloqueada == '1');
                const autorizada = (t.autorizada_por_direcao === true || t.autorizada_por_direcao === 't' || t.autorizada_por_direcao == '1');

                const classeLinha = bloqueada ? 'table-light text-muted' : '';
                const iconeBloq   = bloqueada ? '<i class="bi bi-lock-fill text-danger me-1" title="Bloqueada"></i>' : '';

                let acoes = '';

                // Lógica de Botões baseada no Nível de Acesso (window.EHAMIN vem do PHP)
                if (window.EHAMIN) {
                    // ADMIN: Autorizar, Editar, Eliminar
                    if (!autorizada && !bloqueada) {
                        acoes += `<button class="btn btn-sm btn-success me-1" title="Autorizar" onclick="autorizarTarefa(${t.id})"><i class="bi bi-check2-circle"></i></button>`;
                    }
                    if (!bloqueada) {
                        acoes += `<button class="btn btn-sm btn-warning me-1" title="Editar" onclick="abrirModalEditar(${t.id},'${escapar(t.actividade)}','${escapar(t.objectivos)}','${escapar(t.resultado_esperado)}','${t.id_periodo}','${t.estado}','${t.id_departamento}')"><i class="bi bi-pencil"></i></button>`;
                    }
                    acoes += `<button class="btn btn-sm btn-danger" title="Eliminar" onclick="eliminarTarefa(${t.id})"><i class="bi bi-trash"></i></button>`;
                } else {
                    // Removemos a exigência de "autorizada" para o Diretor poder trabalhar logo
                    if (!bloqueada && !t.id_funcionario) {
                        acoes += `<button class="btn btn-sm btn-primary me-1" title="Atribuir" onclick="abrirAtribuir(${t.id})"><i class="bi bi-person-plus"></i></button>`;
                    }
                    if (!bloqueada) {
                        acoes += `<button class="btn btn-sm btn-warning me-1" title="Editar" onclick="abrirModalEditar(${t.id},'${escapar(t.actividade)}','${escapar(t.objectivos)}','${escapar(t.resultado_esperado)}','${t.id_periodo}','${t.estado}','')"><i class="bi bi-pencil"></i></button>`;
                    }
                }

                // Badge de Autorização (Só aparece para Admin ver o status)
                const badgeAuth = !autorizada
                    ? '<span class="badge bg-warning text-dark ms-1" style="font-size:.65rem">Pendente</span>'
                    : '<span class="badge bg-success ms-1" style="font-size:.65rem">Autorizada</span>';

                return `
                    <tr class="${classeLinha}">
                        <td>
                            ${iconeBloq}<strong>${t.actividade}</strong>
                            ${window.EHAMIN ? '<br>' + badgeAuth : ''}
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                ${t.nome_departamento || t.id_departamento || '—'}
                            </span>
                        </td>
                        <td>
                            ${t.id_funcionario
                                ? '<span class="text-success small"><i class="bi bi-person-check-fill me-1"></i>Atribuído</span>'
                                : '<span class="text-muted small">Não atribuído</span>'}
                        </td>
                        <td><small><i class="bi bi-calendar3 me-1"></i>${formatarData(t.prazo_execucao)}</small></td>
                        <td>${badgeEstado(t.estado)}</td>
                        <td class="text-center">${acoes}</td>
                    </tr>`;
            }).join('');
        })
        .catch(err => {
            console.error('Erro Fatal:', err);
            corpo.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center text-danger py-4">
                        <i class="bi bi-exclamation-triangle me-2"></i>Erro ao processar dados. Verifique a consola.
                    </td>
                </tr>`;
        });
}


function abrirModal() {
    document.getElementById('modal-titulo').innerHTML   = '<i class="bi bi-plus-circle me-2"></i>Nova Tarefa';
    document.getElementById('tarefa-id').value          = '';
    document.getElementById('tarefa-actividade').value  = '';
    document.getElementById('tarefa-objectivos').value  = '';
    document.getElementById('tarefa-resultado').value   = '';
    document.getElementById('tarefa-periodo').value     = '';
    const deptEl = document.getElementById('tarefa-dept');
    if (deptEl) deptEl.value = '';
    document.getElementById('fundo-modal').classList.add('aberto');
}

function abrirModalEditar(id, actividade, objectivos, resultado, id_periodo, estado, dept) {
    document.getElementById('modal-titulo').innerHTML   = '<i class="bi bi-pencil me-2"></i>Editar Tarefa';
    document.getElementById('tarefa-id').value          = id;
    document.getElementById('tarefa-actividade').value  = actividade;
    document.getElementById('tarefa-objectivos').value  = objectivos;
    document.getElementById('tarefa-resultado').value   = resultado;
    document.getElementById('tarefa-periodo').value     = id_periodo || '';
    const deptEl = document.getElementById('tarefa-dept');
    if (deptEl && dept) deptEl.value = dept;
    document.getElementById('fundo-modal').classList.add('aberto');
}

function fecharModal() {
    document.getElementById('fundo-modal').classList.remove('aberto');
}

function guardarTarefa() {
    const form = document.getElementById('form-tarefa');
    const btn = document.querySelector('.botao-guardar');

    // Se o formulário não existir, vamos avisar exatamente o que falta
    if (!form) {
        alert("Erro técnico: O elemento HTML 'form-tarefa' não foi encontrado.");
        console.error("Verifique se o seu <form> no PHP tem id='form-tarefa'");
        return;
    }

    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
    }

    const formData = new FormData(form);
    const url = '../acoes_php_BD/tarefa_logica.php?salvar=true';

    fetch(url, { method: 'POST', body: formData })
    .then(r => r.json())
    .then(res => {
        if (res.sucesso) {
            fecharModal();
            carregarTarefas();
            atualizarSino();
        } else {
            alert("Erro: " + res.erro);
        }
    })
    .catch(err => {
        console.error("Erro no fetch:", err);
        alert("Erro ao conectar com o servidor.");
    })
    .finally(() => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-lg me-2"></i>Guardar';
        }
    });
}

function autorizarTarefa(id) {
    if (!confirm('Deseja autorizar esta actividade para execução oficial?')) return;

    const fd = new FormData();
    fd.append('id', id);

    fetch('../acoes_php_BD/tarefa_logica.php?autorizar=true', { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.sucesso) carregarTarefas();
            else alert('Erro ao autorizar: ' + (data.erro || 'Falha no servidor'));
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
    const select = document.getElementById('select-funcionario');
    select.innerHTML = '<option>A carregar...</option>';

    document.getElementById('fundo-atribuir').classList.add('aberto');

    // Busca funcionários (Cria esta rota no tarefa_logica.php ou usa uma existente)
    fetch('../acoes_php_BD/tarefa_logica.php?buscar_funcionarios=true')
    .then(r => r.json())
    .then(res => {
        if(res.sucesso) {
            select.innerHTML = '<option value="">Selecione um técnico...</option>';
            res.dados.forEach(f => {
                select.innerHTML += `<option value="${f.id}">${f.nome}</option>`;
            });
        }
    });
}

function fecharAtribuir() {
    document.getElementById('fundo-atribuir').classList.remove('aberto');
}

function confirmarAtribuicao() {
    const idTarefa = document.getElementById('atribuir-tarefa-id').value;
    const idFunc = document.getElementById('select-funcionario').value;

    if(!idFunc) { alert("Selecione um funcionário!"); return; }

    const formData = new FormData();
    formData.append('id_tarefa', idTarefa);
    formData.append('id_funcionario', idFunc);

    fetch('../acoes_php_BD/tarefa_logica.php?atribuir=true', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(res => {
        if(res.sucesso) {
            fecharAtribuir();
            carregarTarefas(); // Atualiza a tabela para mostrar o nome do técnico
            atualizarSino();   // Atualiza o contador de notificações
        } else {
            alert("Erro ao atribuir: " + res.erro);
        }
    });
}

function formatarData(data) {
    if (!data) return '—';
    return new Date(data).toLocaleDateString('pt-PT', { day: '2-digit', month: 'short', year: 'numeric' });
}

function badgeEstado(estado) {
    const mapa = {
        'Pendente':              'bg-secondary',
        'Pendente de Aprovação': 'bg-warning text-dark',
        'Aguardando':            'bg-info text-dark',
        'Autorizada':            'bg-info text-dark',
        'Em curso':              'bg-primary',
        'Concluída':             'bg-success',
        'Cancelada':             'bg-danger',
    };
    return `<span class="badge ${mapa[estado] || 'bg-secondary'}">${estado}</span>`;
}

function atualizarSino() {
    fetch('../acoes_php_BD/tarefa_logica.php?contar_notificacoes=true')
    .then(r => r.json())
    .then(res => {
        const sino = document.querySelector('.badge-notificacao'); 
        
        // Verificação de segurança: só tenta mexer no sino se ele existir no HTML
        if (sino) {
            if (res.sucesso && res.total > 0) {
                sino.innerText = res.total;
                sino.style.display = 'block';
            } else {
                sino.style.display = 'none';
            }
        }
    })
    .catch(err => console.error("Erro ao atualizar sino:", err));
}
// Chamar ao carregar a página
setInterval(atualizarSino, 7000); // Atualiza a cada 30 segundos
atualizarSino();
