<?php
namespace Solarfield\Batten;

abstract class ViewPlugin {
	private $view;
	private $componentCode;

	/**
	 * @return \Solarfield\Batten\View
	 */
	public function getView() {
		return $this->view;
	}

	public function getCode() {
		return $this->componentCode;
	}

	public function __construct(ViewInterface $aView, $aComponentCode) {
		$this->view = $aView;
		$this->componentCode = (string)$aComponentCode;
	}
}
