<?php declare(strict_types = 1);

use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $containerConfigurator): void {
	$containerConfigurator->workers([
		\AloisJasa\MonorepoTools\Worker\Patch\AssertSettingsWorker::class,

		\AloisJasa\MonorepoTools\Worker\Patch\UpdateReplaceReleaseWorker::class,
		\AloisJasa\MonorepoTools\Worker\Patch\SetCurrentMutualDependenciesReleaseWorker::class,
		\AloisJasa\MonorepoTools\Worker\Patch\WriteApplicationVersionWorker::class,
		\AloisJasa\MonorepoTools\Worker\Patch\UpdateComposerLockWorker::class,

		\AloisJasa\MonorepoTools\Worker\Patch\CommitWorker::class,
		\AloisJasa\MonorepoTools\Worker\Patch\AddPatchTagWorker::class,
		\AloisJasa\MonorepoTools\Worker\Patch\PushCommitWorker::class,
		\AloisJasa\MonorepoTools\Worker\Patch\PushTagWorker::class,
	]);
};
