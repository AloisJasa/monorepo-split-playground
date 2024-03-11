<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use PharIo\Version\Version;
use Throwable;

final class DeletePrepareReleaseBranchWorker extends AbstractReleaseWorker
{
	public function work(Version $version): void
	{
		try {
			$removeBranchLocalCommand = sprintf(
				'git branch -d %s',
				$this->prepareReleaseBranchName($version),
			);
			$removeBranchOriginCommand = sprintf(
				'git push origin -d %s',
				$this->prepareReleaseBranchName($version),
			);

			$this->processRunner->run($removeBranchLocalCommand);
			$this->processRunner->run($removeBranchOriginCommand);
		} catch (Throwable $exception) {
			// nothing to commit
		}
	}


	public function getDescription(Version $version): string
	{
		return sprintf(
			'Delete local+remote branch "%s" for version "%s"',
			$this->prepareReleaseBranchName($version),
			$version->getOriginalString(),
		);
	}
}
