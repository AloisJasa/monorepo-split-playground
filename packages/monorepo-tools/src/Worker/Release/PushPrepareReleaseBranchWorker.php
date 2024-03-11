<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use PharIo\Version\Version;
use Throwable;

final class PushPrepareReleaseBranchWorker extends AbstractReleaseWorker
{
	public function work(Version $version): void
	{
		try {
			$gitAddCommitCommand = sprintf(
				'git push --set-upstream origin "%s"',
				$this->prepareReleaseBranchName($version),
			);
			$this->processRunner->run($gitAddCommitCommand);
		} catch (Throwable $exception) {
			// nothing to commit
		}
	}


	public function getDescription(Version $version): string
	{
		return sprintf(
			'Push prepare release branch "%s" for version "%s" to remote.',
			$this->prepareReleaseBranchName($version),
			$version->getOriginalString(),
		);
	}
}
