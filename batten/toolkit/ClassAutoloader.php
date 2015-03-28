<?php
namespace batten;

use app\Environment as Env;

class ClassAutoloader {
	private $controller;

	public function handleClassAutoload($aClass) {
		if (preg_match('/^(?:(.+)\\\\)?(.+)$/', $aClass, $matches)) {
			$namespace = $matches[1];
			$className = $matches[2];

			$chain = array_reverse($this->controller->getChain($this->controller->getCode()));

			foreach ($chain as $link) {
				if ($link['namespace'] === $namespace) {
					$tempPath = $link['path'] . $link['classPath'] . DIRECTORY_SEPARATOR . $className . '.php';

					if (file_exists($tempPath)) {
						/** @noinspection PhpIncludeInspection */
						include_once $tempPath;

						if (\batten\DEBUG_CLASS_AUTOLOAD) {
							Env::getLogger()->debug('Autoloaded class ' . $aClass . ' from file ' . $tempPath . '.');
						}

						break;
					}
				}
			}
		}
	}

	public function __construct(ControllerInterface $aController) {
		$this->controller = $aController;
	}
}