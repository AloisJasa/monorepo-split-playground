<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use AloisJasa\MonorepoTools\Worker\Exception\WrongVersionException;
use PharIo\Version\Version;

final class VersionValidationWorker extends AbstractReleaseWorker
{
	public function work(Version $version): void
	{
		$this->assertVersion($version->getOriginalString());
	}


	public function getDescription(Version $version): string
	{
		return sprintf('Version validation.');
	}


	private function assertVersion(string $version): void
	{
		$pattern = '/^\d{1,3}.\d{1,3}.0$/';

		if ($version !== '0.0.0' && preg_match($pattern, $version)) {
			return;
		}

		throw new WrongVersionException();
	}
}
