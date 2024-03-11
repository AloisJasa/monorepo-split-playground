<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker\Patch;

use AloisJasa\MonorepoTools\Stage;
use AloisJasa\MonorepoTools\Worker\AbstractWorker;

abstract class AbstractPatchWorker extends AbstractWorker
{
	final public function getStage(): string
	{
		return Stage::PATCH->value;
	}
}
