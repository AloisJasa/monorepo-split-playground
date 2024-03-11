<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use PharIo\Version\Version;
use Throwable;

final class CreatePrepareReleaseBranchWorker extends AbstractReleaseWorker
{
	public function work(Version $version): void
	{
		try {
			$newBranch = $this->prepareReleaseBranchName($version);
			$gitAddCommitCommand = sprintf('git checkout -b "%s"', $newBranch);
			$this->processRunner->run($gitAddCommitCommand);
			$this->assertCurrentBranch($newBranch);
		} catch (Throwable $exception) {
			// nothing to commit
		}
	}


	public function getDescription(Version $version): string
	{
		return sprintf(
			'Create new prepare release branch "%s" for version "%s"',
			$this->prepareReleaseBranchName($version),
			$version->getOriginalString(),
		);
	}
}
