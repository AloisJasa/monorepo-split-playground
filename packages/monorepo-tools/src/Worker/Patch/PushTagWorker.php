<?php declare (strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Patch;

use PharIo\Version\Version;

final class PushTagWorker extends AbstractPatchWorker
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
