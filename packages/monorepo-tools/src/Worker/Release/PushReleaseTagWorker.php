<?php declare (strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use PharIo\Version\Version;

final class PushReleaseTagWorker extends AbstractReleaseWorker
{
	public function work(Version $version): void
	{
		$this->processRunner->run(sprintf('git push origin %s', $version->getVersionString()));
	}


	public function getDescription(Version $version): string
	{
		return \sprintf('Push "%s" tag to remote repository', $version->getVersionString());
	}
}
