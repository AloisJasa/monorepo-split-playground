<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use PharIo\Version\Version;
use Throwable;

final class CommitPrepareCommitWorker extends AbstractReleaseWorker
{
	public function work(Version $version): void
	{
		try {
			$this->gitAdd('composer.json');
			$this->gitAdd('./**/composer.json');
			$this->commit(sprintf(
				'Prepare release files for version "%s"',
				$version->getOriginalString(),
			));
		} catch (Throwable $exception) {
			// nothing to commit
		}
	}


	public function getDescription(Version $version): string
	{
		return sprintf(
			'Commit prepare release files for version "%s"',
			$version->getOriginalString(),
		);
	}
}
