<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Command;

use TYPO3\Flow\Annotations as Flow;
use TYPO3\Flow\Utility\Algorithms;

final class CreateSiteNode
{
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

    public function __construct(string $workspaceId, string $name)
    {
        $this->nodeId = Algorithms::generateUUID();
        $this->workspaceId = $workspaceId;
        $this->name = $name;
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

}