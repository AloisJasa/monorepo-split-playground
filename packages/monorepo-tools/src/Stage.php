<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools;

enum Stage: string
{
	case RELEASE = 'release';
	case PATCH = 'patch';
}
