<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Release;

use AloisJasa\MonorepoTools\Stage;
use AloisJasa\MonorepoTools\Worker\AbstractWorker;

abstract class AbstractReleaseWorker extends AbstractWorker
{
	final public function getStage(): string
	{
		return Stage::RELEASE->value;
	}
}
