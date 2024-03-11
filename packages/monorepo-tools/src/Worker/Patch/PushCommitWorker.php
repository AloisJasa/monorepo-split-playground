<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Patch;

use PharIo\Version\Version;
use Throwable;

final class PushCommitWorker extends AbstractPatchWorker
{
	public function work(Version $version): void
	{
		try {
			$this->processRunner->run('git push');
		} catch (Throwable $exception) {
			// nothing to commit
		}
	}


	public function getDescription(Version $version): string
	{
		return sprintf(
			'Push commit to "%s" for version "%s" to remote.',
			$this->releaseBranchName($version),
			$version->getOriginalString(),
		);
	}
}
