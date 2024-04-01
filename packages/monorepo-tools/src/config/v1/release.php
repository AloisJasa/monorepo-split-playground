<?php declare(strict_types = 1);

use Symplify\MonorepoBuilder\Config\MBConfig;

return static function (MBConfig $containerConfigurator): void {
	$containerConfigurator->workers([
		// 0. validation
		\AloisJasa\MonorepoTools\Worker\Release\VersionValidationWorker::class,
		\AloisJasa\MonorepoTools\Worker\Release\CheckoutDefaultBranchWorker::class,
		\AloisJasa\MonorepoTools\Worker\Release\AssertTagDoestNotExistsYetWorker::class,

		// 1.create branch
		\AloisJasa\MonorepoTools\Worker\Release\CreatePrepareReleaseBranchWorker::class,

		// 2.commit prepare-commit
		\AloisJasa\MonorepoTools\Worker\Release\UpdateReplaceReleaseWorker::class,
		\AloisJasa\MonorepoTools\Worker\Release\SetCurrentMutualDependenciesReleaseWorker::class,
		\AloisJasa\MonorepoTools\Worker\Release\UpdateCurrentBranchAliasReleaseWorker::class,
		\AloisJasa\MonorepoTools\Worker\Release\WriteApplicationVersionWorker::class,
		\AloisJasa\MonorepoTools\Worker\Release\UpdateComposerLockWorker::class,
		\AloisJasa\MonorepoTools\Worker\Release\CommitPrepareCommitWorker::class,
		\AloisJasa\MonorepoTools\Worker\Release\AddPrepareReleaseTagWorker::class,

		// 3.commit open-dev
		\AloisJasa\MonorepoTools\Worker\Release\SetNextMutualDependenciesReleaseWorker::class,
		\AloisJasa\MonorepoTools\Worker\Release\UpdateNextBranchAliasReleaseWorker::class,
		\AloisJasa\MonorepoTools\Worker\Release\CommitOpenDevWorker::class,

		// 4. push
		\AloisJasa\MonorepoTools\Worker\Release\PushPrepareReleaseBranchWorker::class,

		// 5. merge prepare branch
		\AloisJasa\MonorepoTools\Worker\Release\CheckoutDefaultBranchWorker::class,
		\AloisJasa\MonorepoTools\Worker\Release\MergePrepareBranchWorker::class,

		// 6. create release branch
		\AloisJasa\MonorepoTools\Worker\Release\CreateReleaseBranchWorker::class,

		// 7. commit release candidate commit
		\AloisJasa\MonorepoTools\Worker\Release\CommitEmptyToReleaseBranchWorker::class,
		\AloisJasa\MonorepoTools\Worker\Release\PushReleaseBranchWorker::class,
		\AloisJasa\MonorepoTools\Worker\Release\AddReleaseTagWorker::class,
		\AloisJasa\MonorepoTools\Worker\Release\PushReleaseTagWorker::class,

		// 8. delete prepare branch
		\AloisJasa\MonorepoTools\Worker\Release\DeletePrepareReleaseBranchWorker::class,

		\AloisJasa\MonorepoTools\Worker\Release\CheckoutDefaultBranchLastStepWorker::class,
	]);
};
