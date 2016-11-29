<?php
namespace Wwwision\CrTest\Domain\Aggregate\NodeTree;

use Neos\Cqrs\EventStore\AbstractEventSourcedRepository;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 *
 * @method NodeTree get(string $identifier)
 */
final class NodeTreeRepository extends AbstractEventSourcedRepository
{

}