<?php declare (strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use MonorepoBuilder202211\Symplify\PackageBuilder\Parameter\ParameterProvider;
use PharIo\Version\Version;
use Symplify\MonorepoBuilder\DevMasterAliasUpdater;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;
use Symplify\MonorepoBuilder\Utils\VersionUtils;
use Symplify\MonorepoBuilder\ValueObject\Option;

/**
 * @see https://github.com/symplify/monorepo-builder/blob/main/packages/Release/ReleaseWorker/UpdateBranchAliasReleaseWorker.php
 */
final class UpdateCurrentBranchAliasReleaseWorker extends AbstractReleaseWorker
{
	public function __construct(
		protected ProcessRunner $processRunner,
		protected ComposerJsonProvider $composerJsonProvider,
		private readonly ParameterProvider $parameterProvider,
		private readonly DevMasterAliasUpdater $devMasterAliasUpdater,
		private readonly VersionUtils $versionUtils
	)
	{
		parent::__construct($processRunner, $composerJsonProvider);
	}


	public function work(Version $version): void
	{
		$currentAlias = $this->getCurrentAliasFormat($version);
		$this->devMasterAliasUpdater->updateFileInfosWithAlias(
			$this->composerJsonProvider->getPackagesComposerFileInfos(),
			$currentAlias,
		);
	}


	public function getDescription(Version $version): string
	{
		$nextAlias = $this->versionUtils->getNextAliasFormat($version);

		return sprintf('Set branch alias "%s" to all packages', $nextAlias);
	}


	public function getCurrentAliasFormat(Version $version): string
	{
		$packageAliasFormat = $this->parameterProvider->provideStringParameter(Option::PACKAGE_ALIAS_FORMAT);

		return \str_replace(
			['<major>', '<minor>'],
			[$version->getMajor()->getValue(), $version->getMinor()->getValue()],
			$packageAliasFormat,
		);
	}
}
