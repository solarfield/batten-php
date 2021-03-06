<?php
namespace Solarfield\Batten;

use Solarfield\Ok\EventTargetTrait;

class StandardOutput {
	use EventTargetTrait;

	public function write($aText) {
		$this->dispatchEvent(new StandardOutputEvent($this, $aText));
	}
}
