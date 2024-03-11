<?php declare(strict_types = 1);

use AloisJasa\MonorepoTools\Git\TagResolver;
use Symplify\ComposerJsonManipulator\ValueObject\ComposerJsonSection;
use Symplify\MonorepoBuilder\Config\MBConfig;
use Symplify\MonorepoBuilder\Contract\Git\TagResolverInterface;
use Symplify\MonorepoBuilder\ValueObject\Option;

return static function (MBConfig $containerConfigurator): void {
	$containerConfigurator->import(__DIR__ . '/release.php');
	$containerConfigurator->import(__DIR__ . '/patch-release.php');

	$parameters = $containerConfigurator->parameters();

	$parameters->set('stages_to_allow_existing_tag', ['patch']);

	// require "--stage <name>" when release command is run
	$parameters->set(Option::IS_STAGE_REQUIRED, true);

	$containerConfigurator->dataToAppend([
		ComposerJsonSection::REQUIRE_DEV => [
			'phpstan/phpstan' => '^1.7.14',
			'phpstan/phpstan-doctrine' => '^1.3.9',
			'phpstan/phpstan-nette' => '^1.0.0',
			'phpstan/phpstan-phpunit' => '^1.1',
			'phpunit/phpunit' => '^9.5 || ^10',
			'symplify/monorepo-builder' => '11.1.17',
		],
	]);

	$services = $containerConfigurator->services();

	$services->defaults()
		->autowire()
		->autoconfigure();

	$services->set(TagResolverInterface::class, TagResolver::class);
};
