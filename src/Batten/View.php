<?php
namespace Batten;

use App\Environment as Env;

abstract class View implements ViewInterface {
	use EventTargetTrait;

	private $code;
	private $model;
	private $input;
	private $controller;
	private $plugins;
	private $options;

	protected $type;

	protected function resolvePlugins() {
		foreach ($this->getController()->getPlugins()->getRegisteredCodes() as $registeredCode) {
			$this->getPlugins()->register($registeredCode);
		}
	}

	protected function resolveOptions() {
		if (Reflector::inSurfaceMethodCall()) {
			$this->dispatchEvent(
				new Event('app-resolve-options', ['target' => $this])
			);

			$this->dispatchEvent(
				new Event('resolve-options', ['target' => $this])
			);
		}
	}

	protected function resolveHintedInput() {

	}

	public function getCode() {
		return $this->code;
	}

	public function getType() {
		return $this->type;
	}

	public function getOptions() {
		if (!$this->options) {
			$this->options = new Options();
		}

		return $this->options;
	}

	public function getPlugins() {
		if (!$this->plugins) {
			$this->plugins = new ViewPlugins($this);
		}

		return $this->plugins;
	}

	public function setModel(ModelInterface $aModel) {
		$this->model = $aModel;
	}

	/**
	 * @return ModelInterface|null
	 */
	public function getModel() {
		return $this->model;
	}

	/**
	 * @return InputInterface
	 */
	public function getHintedInput() {
		if (!$this->input) {
			if ($this->getController()) {
				$this->input = $this->getController()->createInput();
			}
		}

		return $this->input;
	}

	public function setController(ViewControllerProxyInterface $aController) {
		$this->controller = $aController;
	}

	/**
	 * @return ViewControllerProxyInterface
	 */
	public function getController() {
		return $this->controller;
	}

	public function render() {
		if (Reflector::inSurfaceMethodCall()) {
			$this->dispatchEvent(new Event('render', [
				'target' => $this,
			]));
		}
	}

	public function init() {
		//this method provides a hook to resolve plugins, options, etc.

		$this->resolvePlugins();
		$this->resolveOptions();
		$this->resolveHintedInput();
	}

	public function __construct($aCode) {
		if (DEBUG_COMPONENT_LIFETIMES) {
			Env::getLogger()->debug(get_class($this) . "[code=" . $aCode . "] was constructed");
		}

		$this->code = (string)$aCode;

		if ((string)$this->type == '') {
			throw new \Exception(
				"Subclasses of " . __CLASS__ . " must set protected member \$type before calling " . __METHOD__ . "()."
			);
		}
	}

	public function __destruct() {
		if (DEBUG_COMPONENT_LIFETIMES) {
			Env::getLogger()->debug(get_class($this) . "[code=" . $this->getCode() . "] was destructed");
		}
	}
}