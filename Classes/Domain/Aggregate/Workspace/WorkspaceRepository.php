<?php
namespace Wwwision\CrTest\Domain\Aggregate\Workspace;

use Neos\Cqrs\EventStore\AbstractEventSourcedRepository;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 *
 * @method Workspace get(string $identifier)
 */
final class WorkspaceRepository extends AbstractEventSourcedRepository
{

}