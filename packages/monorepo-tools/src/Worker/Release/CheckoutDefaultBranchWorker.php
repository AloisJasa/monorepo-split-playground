<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use PharIo\Version\Version;

final class CheckoutDefaultBranchWorker extends AbstractReleaseWorker
{
	public function work(Version $version): void
	{
		$gitCheckoutMaster = sprintf('git fetch && git checkout %s', $this->getDefaultBranch());
		$this->processRunner->run($gitCheckoutMaster);
		$this->assertCurrentBranch($this->getDefaultBranch());
	}


	public function getDescription(Version $version): string
	{
		return sprintf('Checkout default branch "%s".', $this->getDefaultBranch());
	}
}
