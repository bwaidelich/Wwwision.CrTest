<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Command;

use TYPO3\Flow\Annotations as Flow;

final class DiscardNode
{
    /**
     * @Flow\Validate(type="NotEmpty")
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