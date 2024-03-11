<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Git;

use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;

final class TagResolver implements TagResolverInterface
{
	/**
	 * @param ProcessRunner $processRunner
	 */
	public function __construct(
		private readonly ProcessRunner $processRunner
	)
	{
	}


	/**
	 * Returns null, when there are no local tags yet
	 */
	public function resolve(string $gitDirectory): ?string
	{
		$defaultBranch = $this->getDefaultBranch();
		if ($defaultBranch === trim($this->processRunner->run('git branch --show-current'))) {
			$tagList = $this->parseTags($this->processRunner->run(['git', 'tag', '-l', '[0-9]*[0-9]', '--sort=committerdate'], $gitDirectory));
		} else {
			$tagList = $this->parseTags($this->processRunner->run(['git','describe', '--tags', '--abbrev=0', '--match', '[0-9]*[0-9]'], $gitDirectory));
		}

		$theMostRecentTag = (string) \array_pop($tagList);
		if ($theMostRecentTag === '') {
			return null;
		}

		return $theMostRecentTag;
	}


	/**
	 * @return string[]
	 */
	private function parseTags(string $commandResult): array
	{
		$tags = \trim($commandResult);
		// Remove all "\r" chars in case the CLI env like the Windows OS.
		// Otherwise (ConEmu, git bash, mingw cli, e.g.), leave as is.
		$normalizedTags = \str_replace("\r", '', $tags);

		return \explode("\n", $normalizedTags);
	}


	private function getDefaultBranch(): ?string
	{
		exec('git remote set-head origin -a');
		exec("git symbolic-ref --short refs/remotes/origin/HEAD | cut -d '/' -f 2", $outputs, $result_code);

		return $result_code === 0 ? $outputs[0] ?? null : null;
	}
}
