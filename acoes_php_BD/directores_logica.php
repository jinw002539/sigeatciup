<?php
	header('Content-Type: application/json');
	session_start();

	// Importa a configuração da base de dados e o motor de e-mail
	require_once('../data/config.php');
	require_once('servico_email.php');

	// Segurança: Apenas a Direção Geral (Admin) pode cadastrar novos diretores
	if (!isset($_SESSION['adminLog']) || $_SESSION['adminLog'] !== true) {
	    echo json_encode(['sucesso' => false, 'erro' => 'Acesso negado.']);
	    exit();
	}

	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	    try {
		// Inicia uma transação para garantir integridade entre as tabelas
		$pdo->beginTransaction();

		$nome    = $_POST['nome'];
		$email   = $_POST['email'];
		$genero  = $_POST['genero'] ?? 'M';
		$bi      = $_POST['bi'];
		$id_dept = $_POST['id_departamento'];
		$nivel   = $_POST['nivel'];

		if (empty($nome) || empty($email) || empty($bi) || empty($id_dept)) {
		    throw new Exception('Preencha todos os campos obrigatórios.');
		}

		// 1. Gerar código de acesso (Prefixo 00 para diretores)
		$codigo = "00." . str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT) . "." . date('Y');

		// 2. Inserir na tabela de directores (status = false pois aguarda definição de senha)
		$sqlDir = "INSERT INTO directores (nome, genero, bi, email, id_departamento, nivel, codigo_acesso, status) 
		           VALUES (:nome, :genero, :bi, :email, :id_dept, :nivel, :codigo, false)";
		
		$stmtDir = $pdo->prepare($sqlDir);
		$stmtDir->execute([
		    'nome'    => $nome,
		    'genero'  => $genero,
		    'bi'      => $bi,
		    'email'   => $email,
		    'id_dept' => $id_dept,
		    'nivel'   => $nivel,
		    'codigo'  => $codigo
		]);

		// 3. Gerar o Token de Acesso Seguro
		$token = bin2hex(random_bytes(32));

		// 4. Salvar o token na tabela tokens_acesso (conforme a estrutura da sua imagem e04863.png)
		$sqlToken = "INSERT INTO tokens_acesso (email, token, data_criacao, usado) 
		             VALUES (:email, :token, CURRENT_TIMESTAMP, false)";
		$stmtToken = $pdo->prepare($sqlToken);
		$stmtToken->execute([
		    'email' => $email,
		    'token' => $token
		]);

		// 5. Tentar enviar o e-mail de convite
		if (enviarLinkAcesso($email, $nome, $token)) {
		    // Se tudo correu bem, confirma as alterações na BD
		    $pdo->commit();
		    echo json_encode([
		        'sucesso' => true, 
		        'codigo'  => $codigo,
		        'mensagem' => "Director cadastrado com sucesso! O convite para definir a senha foi enviado para o e-mail institucional."
		    ]);
		} else {
		    // Se o e-mail falhar, cancela a inserção no banco para não criar lixo
		    throw new Exception('O director foi registado, mas o servidor não conseguiu enviar o e-mail de ativação.');
		}

	    } catch (Exception $e) {
		if ($pdo->inTransaction()) {
		    $pdo->rollBack();
		}
		
		// Trata erro de duplicidade (BI ou E-mail já existentes)
		if (isset($e->errorInfo) && $e->errorInfo[0] == '23505') {
		    echo json_encode(['sucesso' => false, 'erro' => 'Este BI ou E-mail já se encontra registado no sistema.']);
		} else {
		    echo json_encode(['sucesso' => false, 'erro' => $e->getMessage()]);
		}
	    }
	} else {
	    echo json_encode(['sucesso' => false, 'erro' => 'Método de requisição inválido.']);
	}
?>
