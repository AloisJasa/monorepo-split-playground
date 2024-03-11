<?php declare(strict_types = 1);

use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $mbConfig): void {
	$mbConfig->import(__DIR__ . '/vendor/aloisjasa/monorepo-tools/config/v1/config.php');

	$mbConfig->packageDirectories([
		__DIR__ . '/packages',
	]);

	$mbConfig->defaultBranch('main');
};
