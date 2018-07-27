<?php

namespace Absolute\Module\Settings\Manager;

use Absolute\Core\Manager\BaseManager;

class SettingsManager extends BaseManager {


  public function __construct(\Nette\Database\Context $database) {
    parent::__construct($database);
  }

  /* INTERNAL METHODS */

  /* INTERNAL/EXTERNAL INTERFACE */

  public function _getByKey($key) {
    $resultDb = $this->database->fetch('SELECT * FROM settings WHERE `key` = ?',$key);
    if (!$resultDb) {
      return false;
    }
    return $resultDb;
  }  

  public function _getList() {
    $resultDb = $this->database->fetchAll('SELECT * FROM settings');
    if (!$resultDb) {
      return false;
    }
    return $resultDb;
  }  

  /* EXTERNAL METHOD */

  public function getByKey($key) {
    return $this->_getByKey($key);
  }

  public function getList() {
    return $this->_getList();
  }

}

