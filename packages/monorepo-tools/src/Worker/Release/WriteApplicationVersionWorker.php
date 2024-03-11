<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use PharIo\Version\Version;

final class WriteApplicationVersionWorker extends AbstractReleaseWorker
{
	public function work(Version $version): void
	{
		$appFileName = 'app_version';
		$this->processRunner->run(sprintf('echo %s > %s', $version->getOriginalString(), $appFileName));
		foreach ($this->providePackagesShortNames() as $package) {
			$this->processRunner->run(
				sprintf('echo %s > packages/%s/%s', $version->getOriginalString(), $package, $appFileName),
			);
		}

		$this->gitAdd($appFileName);
		$this->gitAdd(sprintf('./**/%s', $appFileName));
	}


	public function getDescription(Version $version): string
	{
		return sprintf('Write current version "%s" to app_version file.', $version->getOriginalString());
	}
}
