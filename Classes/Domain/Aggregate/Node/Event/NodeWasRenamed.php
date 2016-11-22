<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Event;

use Neos\Cqrs\Event\EventInterface;

final class NodeWasRenamed implements EventInterface
{
    /**
     * @var string
     */
    private $nodeContextId;

    /**
     * @var string
     */
    private $newName;


    public function __construct(string $nodeContextId, string $newName)
    {
        $this->nodeContextId = $nodeContextId;
        $this->newName = $newName;
    }

    public function getNodeContextId(): string
    {
        return $this->nodeContextId;
    }

    public function getNewName(): string
    {
        return $this->newName;
    }


}