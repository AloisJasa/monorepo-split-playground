<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use MonorepoBuilder202211\Symplify\ComposerJsonManipulator\FileSystem\JsonFileManager;
use MonorepoBuilder202211\Symplify\SmartFileSystem\SmartFileInfo;
use PharIo\Version\Version;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Release\Exception\MissingComposerJsonException;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;

/**
 * @see https://github.com/symplify/monorepo-builder/blob/main/packages/Release/ReleaseWorker/UpdateReplaceReleaseWorker.php
 */
final class UpdateReplaceReleaseWorker extends AbstractReleaseWorker
{
	public function __construct(
		protected ProcessRunner $processRunner,
		protected ComposerJsonProvider $composerJsonProvider,
		private readonly JsonFileManager $jsonFileManager
	)
	{
		parent::__construct($processRunner, $composerJsonProvider);
	}


	public function work(Version $version): void
	{
		$rootComposerJson = $this->composerJsonProvider->getRootComposerJson();
		$replace = $rootComposerJson->getReplace();
		$packageNames = $this->composerJsonProvider->getPackageNames();
		$newReplace = [];
		foreach (array_keys($replace) as $package) {
			if ( ! in_array($package, $packageNames, true)) {
				continue;
			}
			$newReplace[$package] = $version->getVersionString();
		}
		if ($replace === $newReplace) {
			return;
		}
		$rootComposerJson->setReplace($newReplace);
		$rootFileInfo = $rootComposerJson->getFileInfo();
		if ( ! $rootFileInfo instanceof SmartFileInfo) {
			throw new MissingComposerJsonException();
		}
		$this->jsonFileManager->printJsonToFileInfo($rootComposerJson->getJsonArray(), $rootFileInfo);
	}


	public function getDescription(Version $version): string
	{
		return 'Update "replace" version in "composer.json" to new tag to avoid circular dependencies conflicts';
	}
}
