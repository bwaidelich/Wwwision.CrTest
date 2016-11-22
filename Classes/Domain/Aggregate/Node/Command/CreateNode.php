<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Command;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\Algorithms;

final class CreateNode
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
     * @Flow\Validate(type="StringLength", options={"minimum": 3})
     * @var string
     */
    private $name;

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

    public function __construct(string $workspaceId, string $name, string $position, string $referenceNodeId)
    {
        $this->nodeId = Algorithms::generateUUID();
        $this->workspaceId = $workspaceId;
        $this->name = $name;
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

    public function getName(): string
    {
        return $this->name;
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