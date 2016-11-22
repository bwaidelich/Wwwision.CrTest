<?php
namespace Wwwision\CrTest\Domain\Aggregate\Workspace;

use Neos\Cqrs\EventStore\AbstractEventSourcedRepository;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 *
 * @method Workspace get(string $identifier)
 */
final class WorkspaceRepository extends AbstractEventSourcedRepository
{

}