<?php

namespace data\model;

use data\resource\resource;
use data\model\modelInterface;
use data\model\utils;

class model extends utils implements modelInterface
{   
    public $table;
    public $key;
    public $dicionary = null;
    protected $records;
    protected $error;

    public function __construct(string $table, string $key)
    {
        $this->setTable($table);
        $this->setKey($key);
    }

    /**
     * Informa��es das colunas vis�veis
     *
     * @return void
     */
    public function visibleColumns()
    {
        return array(
            'table'  => 'users',
            'key'    => 'user_id',
            'columns' => array(
                'user_id' => array(
                    'label' => 'Id',
                    'pk'    => true,
                    'type'  => 'integer',
                ),
            ),
        );
    }

    /**
     * Exporta objeto do tipo dicionary
     * 
     * @param string $dicionarySQL
     * 
     * @return object
     */
    public function dicionary()
    {
        if(empty($this->getDicionary())){
            return null;
        }

        $resource = new resource();
        $dicionary = $resource::dicionary($this->getDicionary());
        if(!$dicionary){
            $this->setError($resource::getError());
            return null;
        }
        return $dicionary;
    }

    /**
     * Move o ponteiro para o próximo
     * 
     */
    public function next()
    {
        if(empty($this->getRecords())){
            return false;
        }

        return $this->getRecords()::next();
    }

     /**
     * Move o ponteiro para o anterior
     * 
     */
    public function previous()
    {
        if(empty($this->getRecords())){
            return false;
        }

        return $this->getRecords()::previus();
    }

    /**
     * Move o ponteiro para o primeiro
     * 
     */
    public function first()
    {
        if(empty($this->getRecords())){
            return false;
        }

        return $this->getRecords()::first();
    }

    /**
     * Move o ponteiro para o último
     * 
     */
    public function last()
    {
        if(empty($this->getRecords())){
            return false;
        }

        return $this->getRecords()::last();
    }

    /**
     * Get the value of data
     */ 
    public function getData()
    {
        if(empty($this->getRecords())){
            return false;
        }

        return $this->getRecords()::getData();
    }

    /**
     * Get the value of data
     */ 
    public function getField(string $field)
    {
        if(empty($this->getRecords())){
            return false;
        }

        return $this->getRecords()::getField($field);
    }

    /**
     * Preenche um campo com valor
     *
     * @param string $field
     * @param mixed $value
     * @return bool
     */
    public function setField(string $field, $value)
    {
        if(empty($this->getRecords())){
            return false;
        }

        return $this->getRecords()::setField($field, $value);
    }

    /**
     * Get the value of isEof
     */ 
    public function isEof()
    {
        if(empty($this->getRecords())){
            return false;
        }

        return $this->getRecords()::getisEof();
    }

    /**
     * Cardinalidade Um para Muitos
     *
     * @param object $model
     * @param string $fieldDestine
     * @param string $fieldOrigen
     * @return void
     */
    public function oneForMany(object $model, string $fieldDestine, string $fieldOrigen = null)
    {
        if(!isset($model) && empty($model)){
            return null;
        }

        if(!isset($fieldDestine) && empty($fieldDestine)){
            return null;
        }

        if(!isset($fieldOrigem)){
            $fieldOrigem = $fieldDestine;
        }

        $resource = new resource();

        $sql = sprintf("SELECT
                %3\$s.*
            FROM %1\$s
            JOIN %3\$s ON %3\$s.%4\$s = %1\$s.%2\$s
            WHERE
                %1\$s.%2\$s = %5\$s
            ORDER BY
                %1\$s.%2\$s;",
            $this->getTable(),
            $fieldOrigem,
            $model->getTable(),
            $fieldDestine,
            $this->getField($fieldOrigem)
        );

        if(!$resource::query($sql)){
            return null;
        }

        return $resource;
    }
    
    /**
     * Cardinalidade Muitos para Muitos
     *
     * @param object $model
     * @param string $fieldDestine
     * @param string $fieldOrigen
     * @return void
     */
    public function manyForMany(object $model, string $fieldDestine, string $fieldOrigen = null)
    {
        if(!isset($model) && empty($model)){
            return null;
        }

        if(!isset($fieldDestine) && empty($fieldDestine)){
            return null;
        }

        if(!isset($fieldOrigem)){
            $fieldOrigem = $fieldDestine;
        }

        $resource = new resource();

        $sql = sprintf("SELECT
                %3\$s.*
            FROM %1\$s
            JOIN %3\$s ON %3\$s.%4\$s = %1\$s.%2\$s
            ORDER BY
                %1\$s.%2\$s;",
            $this->getTable(),
            $fieldOrigem,
            $model->getTable(),
            $fieldDestine,
        );

        if(!$resource::query($sql)){
            return null;
        }

        return $resource;
    }

    /**
     * Salva os dados do modelo
     *
     * @return bool
     */
    public function save()
    {
        if(empty($this->getRecords())){
            return false;
        }

        $resource = new resource();

        $sql = $this->queryForSave($this->visibleColumns(), $this->getData());
        if(empty($sql)){
            $this->setError('Erro na gera��o da query de salvamento.');
            return false;
        }

        if(!$resource::query($sql)){
            $this->setError($resource::getError());
            return false;
        }

        return true;
    }

    /**
     * Exp�e o total de linha afetadas pela query
     * @return int
    */
    protected function total()
    {
        if(empty($this->getRecords())){
            return null;
        }

        return $this->getRecords()::total();
    }

    /**
     * Devolve array associativo de todos os registros
     * 
     * @return array|null
     */
    public function asArray()
    {
        if(empty($this->getRecords())){
            return null;
        }
        return $this->getRecords()::asArray();
    }

    /**
     * Executa uma instrução MySQL
     * 
     */
    public function query(string $sql)
    {
        if(empty($this->getRecords())){
            return null;
        }

        return $this->getRecords()::query($sql);
    }

    /**
     * Salva os dados do modelo
     *
     * @return bool
     */
    public function delete()
    {
        if(empty($this->getRecords())){
            return false;
        }

        $resource = new resource();

        $sql = $this->queryForDelete($this->visibleColumns(), $this->getData());
        if(empty($sql)){
            $this->setError('Erro na gera��o da query de dele��o.');
            return false;
        }

        if(!$resource::query($sql)){
            $this->setError($resource::getError());
            return false;
        }

        return true;
    }

    /**
     * Carrega a propriedade records com um resource
     *
     * @return void
     */
    public function records(string $sql = null)
    {
        $this->records = new resource();
        if(isset($sql)){
            $this->records::query($sql);
            return true;
        }
        $this->records::query("SELECT * FROM ".$this->getTable().";");
        return true;
    }

    /**
     * Busca entre os registros da tabela
     *
     * @param array $search
     * @return void
     */
    public function seek(array $search)
    {
        if(empty($this->getTable())){
            return null;
        }

        $this->setRecords(new resource());
        if(!$this->getRecords()::seek($this->getTable(), $search)){
            $this->setError($this->getRecords()::getError());
            return null;
        }

        return $this;
    }

    /**
     * Busca entre os registros
     *
     * @param string $table
     * @return bool
     */
    public function search(array $search)
    {
        if(empty($this->getTable())){
            return null;
        }

        $this->setRecords(new resource());
        if(!$this->getRecords()::search($this->getTable(), $search)){
            $this->setError($this->getRecords()::getError());
            return null;
        }

        return $this;
    }

    public function isNew()
    {
        if(empty($this->getRecords())){
            return null;
        }
        return $this->getRecords()::getNew();
    }

    /**
     * Colhe o valor para table
     */ 
    public function getTable()
    {
        return $this->table;
    }

    /**
     * Define o valor para table
     *
     * @param string $table
     *
     * @return  self
     */ 
    public function setTable(string $table)
    {
        if(isset($table) && !empty($table)){
            $this->table = $table;
        }
    }

    /**
     * Colhe o valor para key
     */ 
    public function getKey()
    {
        return $this->key;
    }

    /**
     * Define o valor para key
     *
     * @param string $key
     *
     * @return  self
     */ 
    public function setKey(string $key)
    {
        if(isset($key) && !empty($key)){
            $this->key = $key;
        }
    }

    /**
     * Get the value of records
     */ 
    public function getRecords()
    {
        return $this->records;
    }

    protected function setRecords($records)
    {
        if(isset($records) && !empty($records)){
            $this->records = $records;
        }
    }

    /**
     * Get the value of dicionary
     */ 
    public function getDicionary()
    {
        return $this->dicionary;
    }

    /**
     * Set the value of dicionary
     *
     * @return  self
     */ 
    protected function setDicionary($dicionary)
    {
        if(isset($dicionary) && !empty($dicionary)){
            $this->dicionary = $dicionary;
        }
        
        return $this;
    }

    /**
     * Get the value of error
     */ 
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set the value of error
     *
     * @return  self
     */ 
    public function setError($error)
    {
        if(isset($error) && !empty($error)){
            $this->error = $error;
        }
        return $this;
    }
}
