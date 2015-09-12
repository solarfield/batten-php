<?php
namespace Batten;

use App\Environment as Env;
use Ok\MiscUtils;
use Ok\StringUtils;
use Ok\StructUtils;

class ComponentResolver {
	public function resolveComponent($aChain, $aClassNamePart, $aViewTypeCode = null, $aPluginCode = null) {
		$chain = $aChain;
		$chain = array_reverse($chain, true);

		$component = null;

		foreach ($chain as $link) {
			$link = array_replace([
				'namespace' => null,
				'path' => null,
				'pluginsSubNamespace' => '\\Plugins',
				'pluginsSubPath' => '/Plugins',
			], $link);

			$classNamespace = $link['namespace'];
			$className = $this->generateClassName($link, $aClassNamePart, $aViewTypeCode, $aPluginCode);
			$classFileName = $className . '.php';

			$includePath = $link['path'];
			if ($aPluginCode) {
				$pluginNamespace = $aPluginCode;
				$pluginDir = $pluginNamespace;

				$classNamespace .= $link['pluginsSubNamespace'];
				$classNamespace .= '\\' . $pluginNamespace;

				$includePath .= $link['pluginsSubPath'];
				$includePath .= '/' . $pluginDir;
			}
			$includePath .= '/' . $classFileName;

			$realIncludePath = realpath($includePath);

			if ($realIncludePath !== false) {
				$component = [
					'className' => $classNamespace . '\\' . $className,
					'includeFilePath' => $realIncludePath,
				];

				break;
			}
		}

		if (\App\DEBUG && Env::getOptions()->get('debugComponentResolution')) {
			Env::getLogger()->debug(
				get_called_class() . "::" . __FUNCTION__ . "() resolved '"
				. $aPluginCode . $aViewTypeCode . $aClassNamePart
				. "' component " . MiscUtils::varInfo($component)
				. " from chain " . MiscUtils::varInfo($chain)
			);
		}

		return $component;
	}

	public function generateClassName($aLink, $aClassNamePart, $aViewTypeCode = null, $aPluginCode = null) {
		$className = '';

		if ($aViewTypeCode != null) $className .= $aViewTypeCode;

		$className .= $aClassNamePart;

		return $className;
	}
}
