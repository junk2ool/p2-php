<?php
require_once dirname(__FILE__) . '/KeyValuePersister.php';

// {{{ KeyValuePersisterIterator

/**
 * KeyValuePersister用イテレータ
 */
class KeyValuePersisterIterator implements Iterator
{
    // {{{ private properties

    /**
     * KeyValuePersisterのインスタンス
     *
     * @var KeyValuePersister
     */
    private $_kvp;

    /**
     * KeyValuePersister::getIds()が返すIDのリスト
     *
     * @var array
     */
    private $_ids;

    /**
     * $_idsの内部ポインタが指す値
     *
     * @var int
     */
    private $_currentId;

    /**
     * $_currentIdに対応するキー
     *
     * @var string
     */
    private $_currentKey;

    /**
     * $_currentIdに対応する値
     *
     * @var mixed
     */
    private $_currentValue;

    // }}}
    // {{{ _fetchCurrent()

    /**
     * $_currentKeyと$_currentValueを取得する
     *
     * @param void
     * @return void
     */
    private function _fetchCurrent()
    {
        if ($this->_currentId === false ||
            ($pair = $this->_kvp->findById($this->_currentId)) === null)
        {
            $this->_currentKey = $this->_currentValue = null;
        } else {
            $this->_currentKey = $pair['key'];
            $this->_currentValue = $pair['value'];
        }
    }

    // }}}
    // {{{ constructor

    /**
     * コンストラクタ
     *
     * @param KeyValuePersister $kvp
     */
    public function __construct(KeyValuePersister $kvp)
    {
        $this->_kvp = $kvp;
        $this->_ids = $kvp->getIds();
        $this->_currentId = false;
    }

    // }}}
    // {{{ current()

    /**
     * Iterator::current()
     *
     * @param void
     * @return mixed
     */
    public function current()
    {
        return $this->_currentValue;
    }

    // }}}
    // {{{ key()

    /**
     * Iterator::key()
     *
     * @param void
     * @return string
     */
    public function key()
    {
        return $this->_currentKey;
    }

    // }}}
    // {{{ next()

    /**
     * Iterator::next()
     *
     * @param void
     * @return void
     */
    public function next()
    {
        $this->_currentId = next($this->_ids);
        $this->_fetchCurrent();
    }

    // }}}
    // {{{ rewind()

    /**
     * Iterator::rewind()
     *
     * @param void
     * @return void
     */
    public function rewind()
    {
        $this->_currentId = reset($this->_ids);
        $this->_fetchCurrent();
    }

    // }}}
    // {{{ valid()

    /**
     * Iterator::valid()
     *
     * @param void
     * @return bool
     */
    public function valid()
    {
        return $this->_currentId !== false;
    }

    // }}}
}

// }}}

/*
 * Local Variables:
 * mode: php
 * coding: cp932
 * tab-width: 4
 * c-basic-offset: 4
 * indent-tabs-mode: nil
 * End:
 */
// vim: set syn=php fenc=cp932 ai et ts=4 sw=4 sts=4 fdm=marker:
