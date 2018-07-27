<?php

namespace Absolute\Module\Settings\Manager;

use Absolute\Core\Manager\BaseCRUDManager;
use Nette\Database\Context;

class SettingsCRUDManager extends BaseCRUDManager {


	public function __construct(Context $database) {
  	parent::__construct($database);
	}

	// OTHER METHODS

  // CONNECT METHODS

  // CUD METHODS

	public function update($key, $value) 
	{
    $resultDb = $this->database->table('settings')->where('key', $key)->fetch();
    if ($resultDb === false) 
    {
			$this->database->table('settings')->insert(array(
				'key' => $key,
				'value' => $value,
			));
			return true;
	  }
		$this->database->table('settings')->where('id', $resultDb->id)->update(array(
			'key' => $key,
			'value' => $value,
		));	
		return true;
	}

}

