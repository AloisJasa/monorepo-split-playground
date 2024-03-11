<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Patch;

use PharIo\Version\Version;

final class AddPatchTagWorker extends AbstractPatchWorker
{
	public function work(Version $version): void
	{
		$this->processRunner->run('git tag ' . $version->getOriginalString());
		$this->assertTagExists($version->getOriginalString());
	}


	public function getDescription(Version $version): string
	{
		return sprintf('Add patch tag "%s"', $version->getOriginalString());
	}
}
