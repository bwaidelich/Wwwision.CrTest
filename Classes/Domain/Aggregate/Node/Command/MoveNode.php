<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Command;

use TYPO3\Flow\Annotations as Flow;

final class MoveNode
{
    const POSITION_BEFORE = 'before';
    const POSITION_INTO = 'into';
    const POSITION_AFTER = 'after';

    /**
     * @Flow\Validate(type="NotEmpty")
     * @var string
     */
    private $nodeId;

    /**
     * @Flow\Validate(type="NotEmpty")
     * @var string
     */
    private $workspaceId;

    /**
     * @Flow\Validate(type="NotEmpty")
     * @Flow\Validate(type="RegularExpression", options={"regularExpression": "/^(before|into|after)$/"})
     * @var string one of the POSITION_* constants
     */
    private $position;

    /**
     * @Flow\Validate(type="NotEmpty")
     * @var string
     */
    private $referenceNodeId;

    public function __construct(string $nodeId, string $workspaceId, string $position, string $referenceNodeId)
    {
        $this->nodeId = $nodeId;
        $this->workspaceId = $workspaceId;
        $this->position = $position;
        $this->referenceNodeId = $referenceNodeId;
    }

    public function getNodeId(): string
    {
        return $this->nodeId;
    }

    public function getWorkspaceId(): string
    {
        return $this->workspaceId;
    }

    public function getPosition(): string
    {
        return $this->position;
    }

    public function getReferenceNodeId(): string
    {
        return $this->referenceNodeId;
    }
}