<?php declare(strict_types = 1);

namespace AloisJasa\MonorepoTools\Worker;

use Nette\Utils\Strings;
use AloisJasa\MonorepoTools\Worker\Exception\TagAlreadyExistsException;
use AloisJasa\MonorepoTools\Worker\Exception\TagDoesntExistsException;
use AloisJasa\MonorepoTools\Worker\Exception\WrongBranchException;
use PharIo\Version\Version;
use Symplify\MonorepoBuilder\FileSystem\ComposerJsonProvider;
use Symplify\MonorepoBuilder\Release\Contract\ReleaseWorker\StageAwareInterface;
use Symplify\MonorepoBuilder\Release\Process\ProcessRunner;

abstract class AbstractWorker implements StageAwareInterface
{
	public function __construct(
		protected ProcessRunner $processRunner,
		protected ComposerJsonProvider $composerJsonProvider,
	)
	{
	}


	protected function prepareReleaseBranchName(Version $version): string
	{
		return sprintf("v%s.%s-%s", $version->getMajor()->getValue(), $version->getMinor()->getValue(), 'prepare');
	}


	protected function releaseBranchName(Version $version): string
	{
		return sprintf("%s%s.%s", 'v', $version->getMajor()->getValue(), $version->getMinor()->getValue());
	}


	protected function gitAdd(string $file): void
	{
		$this->processRunner->run(sprintf('git add %s', $file));
	}


	protected function commit(string $message): void
	{
		$this->processRunner->run('git commit --message="' . addslashes($message) . '"');
	}


	protected function getDefaultBranch(): ?string
	{
		exec('git remote set-head origin -a');
		exec("git symbolic-ref --short refs/remotes/origin/HEAD | cut -d '/' -f 2", $outputs, $result_code);

		return $result_code === 0 ? $outputs[0] ?? null : null;
	}


	protected function prepareReleaseTagName(string $version): string
	{
		return sprintf(
			'v%s-%s',
			$version,
			'prepare',
		);
	}


	protected function providePackagesShortNames(): array
	{
		return array_map(
			static function ($name) {
				return (string) Strings::after($name, '/', -1);
			},
			$this->composerJsonProvider->getPackageNames(),
		);
	}


	protected function assertCurrentBranch(string $branch): void
	{
		if ($branch !== trim($this->processRunner->run('git branch --show-current'))) {
			throw new WrongBranchException();
		}
	}


	protected function assertTagDoesNotExists(string $tag): void
	{
		if (trim($this->processRunner->run(\sprintf('git tag -l "%s"', $tag))) !== '') {
			throw new TagAlreadyExistsException();
		}
	}


	protected function assertTagExists(string $tag): void
	{
		if (trim($this->processRunner->run(\sprintf('git tag -l "%s"', $tag))) !== $tag) {
			throw new TagDoesntExistsException();
		}
	}
}
