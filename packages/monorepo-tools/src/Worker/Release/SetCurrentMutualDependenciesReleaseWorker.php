<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use PharIo\Version\Version;
use Symplify\MonorepoBuilder\DependencyUpdater;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Package\PackageNamesProvider;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\Utils\VersionUtils;

/**
 * @see https://github.com/symplify/monorepo-builder/blob/main/packages/Release/ReleaseWorker/SetCurrentMutualDependenciesReleaseWorker.php
 */
final class SetCurrentMutualDependenciesReleaseWorker extends AbstractReleaseWorker
{
	public function __construct(
		protected ProcessRunner $processRunner,
		protected ComposerJsonProvider $composerJsonProvider,
		private readonly VersionUtils $versionUtils,
		private readonly DependencyUpdater $dependencyUpdater,
		private readonly PackageNamesProvider $packageNamesProvider
	)
	{
		parent::__construct($processRunner, $composerJsonProvider);
	}


	public function work(Version $version): void
	{
		$versionInString = $this->versionUtils->getRequiredFormat($version);
		$this->dependencyUpdater->updateFileInfosWithPackagesAndVersion(
			$this->composerJsonProvider->getPackagesComposerFileInfos(),
			$this->packageNamesProvider->provide(),
			$versionInString,
		);
		// give time to propagate values before commit
		sleep(1);
	}


	public function getDescription(Version $version): string
	{
		$versionInString = $this->versionUtils->getRequiredFormat($version);

		return sprintf('Set packages mutual dependencies to "%s" version', $versionInString);
	}
}
