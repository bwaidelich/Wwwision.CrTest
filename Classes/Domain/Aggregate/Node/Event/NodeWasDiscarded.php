<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Event;

use Neos\Cqrs\Event\EventInterface;

final class NodeWasDiscarded implements EventInterface
{
    /**
     * @var string
     */
    private $nodeContextId;

    public function __construct(string $nodeContextId)
    {
        $this->nodeContextId = $nodeContextId;
    }

    public function getNodeContextId(): string
    {
        return $this->nodeContextId;
    }

}