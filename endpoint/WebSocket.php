<?php

require_once './EndPointException.php';

/**
 * @author rubensmarcon
 */
class WebSocket {

    /** @var boolean se alguma conexão está ativa */
    private $conectado;

    /** @var int número da porta */
    private $port;

    /** @var resource Socket que escuta conexões */
    private $serverSocket;
    
    /** @var array<resource> array de sockets */
    private $sockets;

    public function WebSocket($port) {
	$this->port = $port;
	$this->conectado = FALSE;
    }

    /**
     * Começa a escutar conexões e processar as demais funcionalidades
     * @param int $port número da porta
     * @throws EndPointException
     */
    public function listen($port) {
	
	$this->port = $port;
	
	/* @var resource cria o socket que escutará conexões */
	$this->serverSocket = socket_create(AF_INET, SOCK_STREAM, getprotobyname('TCP'));

	/* seta o host e a porta */
	if (!@socket_bind($this->serverSocket, 0, $this->port))
	    throw new EndPointException("socket_bind() falhou: resposta: " . socket_strerror(socket_last_error($this->serverSocket)) );

	/* inicia a escuta de conexões */
	if (!@socket_listen($this->serverSocket)) 
	    throw new EndPointException("socket_listen() falhou: resposta: " . socket_strerror(socket_last_error($this->serverSocket)));
	
	/* cria um array de sockets e adiciona o serverSocket no array */
	$this->sockets = array($this->serverSocket);
	
	$this->loopPrincipal();
	
	/* Fecha todos os sockets e encerra a aplicação */
	foreach ($this->sockets as $socket)
	{
	    if (get_resource_type($socket) == "Socket")
		socket_close($socket);
	}
    }
    
    private function loopPrincipal() {
	/* Inicia um loop infinito para ir sempre checando os sockets e tratando os eventos */
	while (true)
	{
	    /** @var resource   cria uma copia do array original de sockets, para manipulá-los sem alterar o original */
	    $changedSockets = $this->sockets;

	    /* obtêm todos os sockets que "ouviram" algo */
	    socket_select($changedSockets, $null = NULL, $null, 0, 10);

	    $this->novaConexao($changedSockets);

	    // itera entre os outros sockets "ouvintes" 
	    foreach ($changedSockets as $clientSocket)
	    {
		// Le um socket e toma uma decisão basada no retorno deste 
		$break = loopLeituraSocket($clientSocket, $this->sockets);
		if ($break == 1)
		    break;
		if ($break == 2)
		    return;

		$this->encerraConexao ($break == 3 ? $null = NULL : $clientSocket);
	    }
	}
    }

    /**
     * Checa se o $serverSocket está na lista dos "ouvintes" e realiza os procedimentos para uma nova conexão
     * @param array<resources> $changedSockets array de sockets
     * @return boolean Se houve uma nova conexão ou não
     * @throws EndPointException
     */
    private function novaConexao(&$changedSockets) {
	
	if (in_array($this->serverSocket, $changedSockets))
	{
	    /* aceita a nova conexão e cria um socket para a seção com este novo usuário */
	    $socketNovo = socket_accept($this->serverSocket);
	    if (!$socketNovo)
		throw new EndPointException("socket_accept() falhou: resposta: " . socket_strerror(socket_last_error($this->serverSocket)));

	    /* adiciona o novo socket no array de sockets */
	    $this->sockets[] = $socketNovo;
	    /* lê os primeiros dados enviados pelo WebSocket do browser, responsáveis pelo 'handshaking' */
	    $header = socket_read($socketNovo, 1024);
	    if ($header == "")
		return FALSE;
	    //logServidor("\n$header\n");

	    /* realiza o processo de 'handshaking' entre o cliente e o servidor */
	    $this->performHandshaking($header, $socketNovo);

	    /* obtêm o endereço de IP do novo socket 
	    socket_getpeername($socketNovo, $ip);

	    /* Registra novo socket conectado no array de sockets sem sala, do controlador de salas 
	    ControladorSalas::addNotGameSocket($socketNovo);
	    logServidor("\nIp $ip solicita conexão\n");*/

	    /* enviar as salas disponíveis ao solicitante */
	    //enviaDadoSocket(array('type' => 'login', 'subtype' => 'init', 'listaInfoSalas' => ControladorSalas::geraInfoSalas()), $socketNovo);
	
	    /* remove o $serverSocket da lista de sockets "ouvintes", pois já foi tratado */
	    $foundSocket = array_search($this->serverSocket, $changedSockets);
	    unset($changedSockets[$foundSocket]);
	    return TRUE;
	}
	else 
	    return FALSE;
    }
    
    private function encerraConexao(&$clientSocket) {
	//detecta um socket desconectado 
	if (!$clientSocket || @socket_read($clientSocket, 1024) === false)
	{
	    $foundSocket = array_search($clientSocket, $this->sockets);
	    ControladorSalas::conector((object) array("type" => 'server', "subtype" => 'removeJogador'), $clientSocket);
	    if (get_resource_type($clientSocket) == "Socket")
		socket_close($clientSocket);
	    // remove o socket desconectado do array $sockets 
	    unset($this->sockets[$foundSocket]);
	    ControladorSalas::delNotGameSocket($clientSocket);
	    /*if ($clientSocket == $adminSocket)
		$adminSocket = NULL;*/
	}
    }

    /**
     * Procedimento de 'handshaking'
     * @link http://en.wikipedia.org/wiki/WebSocket#WebSocket_protocol_handshake
     * @param string $headerRecebido  cabeçalho recebido do cliente
     * @param resource $clientSocket  socket do cliente
     * @return void  
     */
    private function performHandshaking($headerRecebido, $clientSocket, $host) {
	$headers = array();
	$lines = preg_split("/\r\n/", $headerRecebido);
	$matches = NULL;
	foreach ($lines as $line) {
	    $line = chop($line);
	    if (preg_match('/\A(\S+): (.*)\z/', $line, $matches))
		$headers[$matches[1]] = $matches[2];
	}
	//var_dump ($headers);
	$secKey = $headers['Sec-WebSocket-Key'];
	$secAccept = base64_encode(pack('H*', sha1($secKey . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
	/* cabeçalho de resposta */
	$upgrade = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
		"Upgrade: websocket\r\n" .
		"Connection: Upgrade\r\n" .
		"WebSocket-Origin: $host\r\n" .
		"WebSocket-Location: ws://$host:" . $this->port . "/index.php\r\n" .
		"Sec-WebSocket-Accept:$secAccept\r\n\r\n";
	socket_write($clientSocket, $upgrade, strlen($upgrade));
    }

    /**
     * Decodifica uma mensagem recebida do WebSocket do cliente
     * @param string  $text dados "brutos" lidos do WebSocket do cliente 
     * @return string   mensagem legivel
     */
    private function unmask($text) {
	$length = ord($text[1]) & 127;
	if ($length == 126) {
	    $masks = substr($text, 4, 4);
	    $data = substr($text, 8);
	} elseif ($length == 127) {
	    $masks = substr($text, 10, 4);
	    $data = substr($text, 14);
	} else {
	    $masks = substr($text, 2, 4);
	    $data = substr($text, 6);
	}
	$textRet = "";
	for ($i = 0; $i < strlen($data); ++$i) {
	    $textRet .= $data[$i] ^ $masks[$i % 4];
	}
	return $textRet;
    }

    /**
     * Codifica a mensagem da forma apropriada para ser transferida para o cliente
     * @param string $text  texto legível 
     * @return string   dados codificados de forma adequada ao protocolo dos WebSockets (somente a inclusão de um cabeçalho, neste caso)
     */
    private function mask($text) {
	$b1 = 0x80 | (0x1 & 0x0f);
	$length = strlen($text);

	if ($length <= 125)
	    $header = pack('CC', $b1, $length);
	elseif ($length > 125 && $length < 65536)
	    $header = pack('CCn', $b1, 126, $length);
	elseif ($length >= 65536)
	    $header = pack('CCNN', $b1, 127, $length);
	return $header . $text;
    }

}
