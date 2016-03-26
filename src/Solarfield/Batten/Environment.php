<?php
namespace Solarfield\Batten;

use ErrorException;
use Exception;
use Solarfield\Ok\MiscUtils;

require_once __DIR__ . '/main.php';
require_once \App\DEPENDENCIES_FILE_PATH . '/solarfield/ok-kit-php/src/Solarfield/Ok/MiscUtils.php';

abstract class Environment {
	static private $logger;
	static private $standardOutput;
	static private $vars;

	static public function getBaseChain() {
		return $chain = [
			'solarfield/batten-php' => [
				'namespace' => __NAMESPACE__,
				'path' => __DIR__,
			],

			'app' => [
				'namespace' => 'App',
				'path' => static::getVars()->get('appPackageFilePath') . '/App',
			],
		];
	}

	/**
	 * @return Logger
	 */
	static public function getLogger() {
		if (!self::$logger) {
			self::$logger = new Logger();
		}

		return self::$logger;
	}

	/**
	 * @return StandardOutput
	 */
	static public function getStandardOutput() {
		if (!self::$standardOutput) {
			self::$standardOutput = new StandardOutput();
		}

		return self::$standardOutput;
	}

	static public function getVars() {
		if (!self::$vars) {
			require_once __DIR__ . '/Options.php';
			self::$vars = new Options(['readOnly'=>true]);
		}

		return self::$vars;
	}

	static public function init($aOptions) {
		set_error_handler(function ($aNumber, $aMessage, $aFile, $aLine) {
			throw new ErrorException($aMessage, 0, $aNumber, $aFile, $aLine);
		});

		$vars = static::getVars();

		$vars->add('requestId', MiscUtils::guid());
		$vars->add('appDependenciesFilePath', \App\DEPENDENCIES_FILE_PATH);


		//validate app package file path

		if (!array_key_exists('appPackageFilePath', $aOptions)) {
			throw new Exception(
				"The appPackageFilePath option must be specified when calling " . __METHOD__ . "."
			);
		}

		$path = realpath($aOptions['appPackageFilePath']);

		if (!$path) {
			throw new Exception(
				"Invalid appPackageFilePath: '" . $aOptions['appPackageFilePath'] . "'."
			);
		}

		$vars->add('appPackageFilePath', $path);


		if (\App\DEBUG) {
			$config = $aOptions['config'];

			$vars->add('debugComponentResolution', array_key_exists('debugComponentResolution', $config) ? (bool)$config['debugComponentResolution'] : false);
			$vars->add('debugComponentLifetimes', array_key_exists('debugComponentLifetimes', $config) ? (bool)$config['debugComponentLifetimes'] : false);
			$vars->add('debugMemUsage', array_key_exists('debugMemUsage', $config) ? (bool)$config['debugMemUsage'] : false);
			$vars->add('debugPaths', array_key_exists('debugPaths', $config) ? (bool)$config['debugPaths'] : false);
			$vars->add('debugRouting', array_key_exists('debugRouting', $config) ? (bool)$config['debugRouting'] : false);
			$vars->add('debugReflection', array_key_exists('debugReflection', $config) ? (bool)$config['debugReflection'] : false);
			$vars->add('debugClassAutoload', array_key_exists('debugClassAutoload', $config) ? (bool)$config['debugClassAutoload'] : false);
		}
	}
}
