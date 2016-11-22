<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node;

use Neos\Cqrs\EventStore\AbstractEventSourcedRepository;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 *
 * @method Node get(string $identifier)
 */
final class NodeRepository extends AbstractEventSourcedRepository
{

}