<?php

class TemplateEngineHandler {
	var $engineObj;
	var $engineParams;
	var $templateName;

	function TemplateEngineHandler($engineObj) {
		if (!is_object($engineObj))
			die("The engine object is not a valid object");

		$this->engineObj = $engineObj;
	}

	function display($templateName, $templateParams = array()) {
		switch (get_class($this->engineObj)) {
		case "Smarty":
			if (!empty($templateParams))
				$this->engineObj->assign($templateParams);

			$this->engineObj->display($templateName);
			break;

		case "sfTemplateEngine":
			if (!empty($templateParams))
				echo $this->engineObj->render($templateName, $templateParams);
			else
				echo $this->engineObj->render($templateName);
			break;
		case "ArribaAbajo":
			$this->engineObj->arriba($templateName, $templateParams);
			$this->engineObj->abajo();
			break;
		}
	}
}

?>