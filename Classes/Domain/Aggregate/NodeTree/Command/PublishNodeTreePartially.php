<?php
namespace Wwwision\CrTest\Domain\Aggregate\NodeTree\Command;

use Neos\Flow\Annotations as Flow;

final class PublishNodeTreePartially
{

    /**
     * @Flow\Validate(type="NotEmpty")
     * @var string
     */
    private $workspaceId;

    /**
     * @Flow\Validate(type="NotEmpty")
     * @var string
     */
    private $targetWorkspaceId;

    /**
     * @Flow\Validate(type="NotEmpty")
     * @var string[]
     */
    private $nodeIds;

    /**
     * @param string $workspaceId
     * @param string $targetWorkspaceId
     * @param array $nodes
     */
    public function __construct(string $workspaceId, string $targetWorkspaceId, array $nodes)
    {
        $this->workspaceId = $workspaceId;
        $this->targetWorkspaceId = $targetWorkspaceId;
        $this->nodeIds = [];
        foreach ($nodes as $node) {
            if ($node['include']) {
                $this->nodeIds[] = $node['id'];
            }
        }
    }

    public function getWorkspaceId(): string
    {
        return $this->workspaceId;
    }

    public function getTargetWorkspaceId(): string
    {
        return $this->targetWorkspaceId;
    }

    /**
     * @return string[]
     */
    public function getNodeIds(): array
    {
        return $this->nodeIds;
    }
}