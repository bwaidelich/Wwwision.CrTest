<?php
namespace Wwwision\CrTest\Domain\Aggregate\Workspace;

use Neos\Cqrs\Domain\AbstractEventSourcedAggregateRoot;
use TYPO3\Flow\Annotations as Flow;
use Wwwision\CrTest\Domain\Aggregate\Workspace\Event\WorkspaceWasCreated;

final class Workspace extends AbstractEventSourcedAggregateRoot
{

    static public function create(string $identifier): Workspace
    {
        $workspace = new static($identifier);

        $workspace->recordThat(new WorkspaceWasCreated($identifier));
        return $workspace;
    }

}