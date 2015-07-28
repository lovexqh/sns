<?php

class BaseAction extends Action{
	public function _initialize() {
		$this->assign('modelname',MODULE_NAME);
		$this->assign('appname',APP_NAME);
	}
}