<?php
namespace Wwwision\CrTest\Projection\Workspace;

use Doctrine\ORM\Mapping as ORM;
use TYPO3\Flow\Annotations as Flow;

/**
 * @Flow\Entity
 * @ORM\Table(name="wwwision_crtest_workspace")
 */
class Workspace
{
    /**
     * @ORM\Id
     * @var string
     */
    protected $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isLive()
    {
        return $this->id === 'live';
    }

    /**
     * @return bool
     */
    public function isPersonal()
    {
        return !$this->isLive();
    }

}