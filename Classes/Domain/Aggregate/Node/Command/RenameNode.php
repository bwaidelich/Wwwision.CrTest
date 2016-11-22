<?php
namespace Wwwision\CrTest\Domain\Aggregate\Node\Command;

use TYPO3\Flow\Annotations as Flow;

final class RenameNode
{
    /**
     * @Flow\Validate(type="NotEmpty")
     * @var string
     */
    private $nodeContextId;

    /**
     * @Flow\Validate(type="NotEmpty")
     * @Flow\Validate(type="StringLength", options={"minimum": 3})
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