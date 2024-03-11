<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Patch;

use PharIo\Version\Version;
use Throwable;

final class CommitWorker extends AbstractPatchWorker
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
			'Commit files for patch version "%s"',
			$version->getOriginalString(),
		);
	}
}
