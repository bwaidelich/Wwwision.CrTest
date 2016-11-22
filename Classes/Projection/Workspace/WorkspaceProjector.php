<?php
namespace Wwwision\CrTest\Projection\Workspace;

use Neos\Cqrs\Projection\Doctrine\AbstractDoctrineProjector;
use TYPO3\Flow\Annotations as Flow;
use Wwwision\CrTest\Domain\Aggregate\Workspace\Event\WorkspaceWasCreated;

class WorkspaceProjector extends AbstractDoctrineProjector
{
    public function whenWorkspaceWasCreated(WorkspaceWasCreated $event)
    {
        $workspace = new Workspace($event->getWorkspaceId());
        $this->add($workspace);
    }

    public function get($userIdentifier): Workspace
    {
        /** @var Workspace $workspace */
        $workspace = parent::get($userIdentifier);
        return $workspace;
    }
}