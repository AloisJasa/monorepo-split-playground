<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Patch;

use AloisJasa\MonorepoTools\Worker\Exception\NoChangesDetectedToReleaseException;
use AloisJasa\MonorepoTools\Worker\Exception\WrongVersionException;
use PharIo\Version\Version;

final class AssertSettingsWorker extends AbstractPatchWorker
{
	public function work(Version $version): void
	{
		$this->assertTagDoesNotExists($version->getOriginalString());
		$this->assertVersion($version->getOriginalString());
		$this->assertReleaseBranch($version);
		$this->assertDetectChanges();
	}


	public function getDescription(Version $version): string
	{
		return sprintf('Assert release settings.');
	}


	private function assertVersion(string $version): void
	{
		$pattern = '/^\d{1,3}.\d{1,3}.[1-9]{1,3}$/';

		if ($version !== '0.0.0' && preg_match($pattern, $version)) {
			return;
		}

		throw new WrongVersionException();
	}


	private function assertReleaseBranch(Version $version): void
	{
		$this->assertCurrentBranch($this->releaseBranchName($version));
	}


	private function assertDetectChanges(): void
	{
		if (trim($this->processRunner->run('git tag --points-at HEAD')) !== '') {
			throw new NoChangesDetectedToReleaseException();
		}
	}
}
