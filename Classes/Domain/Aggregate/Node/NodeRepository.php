<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node;

use Neos\Cqrs\EventStore\AbstractEventSourcedRepository;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 *
 * @method Node get(string $identifier)
 */
final class NodeRepository extends AbstractEventSourcedRepository
{

}