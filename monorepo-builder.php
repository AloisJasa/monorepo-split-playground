<?php declare(strict_types = 1);

use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $mbConfig): void {
	$mbConfig->packageDirectories([
		__DIR__ . '/packages',
	]);

	$mbConfig->defaultBranch('main');

	$mbConfig->import(__DIR__ . '/packages/monorepo-tools/src/config/v1/config.php');
};
