<?php

/**
 * Classe DadosModel
 * Não utiliza tabela
 */
class Host extends Model {

    protected static $useTable = "host";
    private $endereco;
    private $dia;

    function Host($data = NULL) {
	if ($data != NULL) {
	    parent::__construct(isset($data['id']) ? (int) $data['id'] : NULL);
	    $this->endereco = isset($data['endereco']) ? utf8_encode($data['endereco']) : NULL;
	    $this->dia = isset($data['dia']) ? utf8_encode($data['dia']) : NULL;
	} else
	    parent::__construct();
    }

    public function toArray() {
		return get_object_vars($this);
    }

}
