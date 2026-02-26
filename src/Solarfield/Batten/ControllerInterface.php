<?php
namespace Solarfield\Batten;

interface ControllerInterface {
	/**
	 * @param string $aCode
	 * @param array $aOptions
	 * @return ControllerInterface
	 */
	static public function fromCode($aCode, $aOptions = array());

	/**
	 * @param string $aCode
	 * @return array
	 */
	static public function getChain($aCode);

	/**
	 * @return ComponentResolver
	 */
	static public function getComponentResolver();

	/**
	 * @param array $aInfo
	 * @return ControllerInterface
	 */
	static public function boot($aInfo = []);

	/**
	 * @return string
	 */
	static public function getInitialRoute();

	/**
* @param array|null $aInfo Route info passed through each iteration of the boot loop.
* @param array $aBootPath A history of the boot loop iterations.
* @return ControllerInterface|null
*/
	public function resolveController($aInfo, &$aBootPath);

	/**
	 * Called if the controller is resolved to the final controller in resolveController().
	 */
	public function markResolved();

	public function processRoute($aInfo);

	public function run();

	public function runTasks();

	public function runRender();

	public function handleException(\Throwable $aEx);

	/**
	 * @return string|null
	 */
	public function getDefaultViewType();

	/**
	 * @return string|null
	 */
	public function getRequestedViewType();

	/**
	 * @return HintsInterface
	 */
	public function createHints();

	/**
	 * @return HintsInterface|null
	 */
	public function getHints();

	public function setHints(HintsInterface $aHints);

	/**
	 * @return InputInterface
	 */
	public function createInput();

	/**
	 * @return InputInterface|null
	 */
	public function getInput();

	public function setInput(InputInterface $aInput);

	/**
	 * @return ModelInterface
	 */
	public function createModel();

	/**
	 * @return ModelInterface|null
	 */
	public function getModel();

	public function setModel(ModelInterface $aModel);

	/**
	 * @param string $aType
	 * @return ViewInterface
	 */
	public function createView($aType);

	/**
	 * @return ViewInterface|null
	 */
	public function getView();

	public function setView(ViewInterface $aView);

	public function getCode();

	/**
	 * @return ControllerPlugins
	 */
	public function getPlugins();

	/**
	 * @return Options
	 */
	public function getOptions();

	public function addEventListener($aEventType, $aListener);

	public function init();
}
