<?php

namespace FL\GmailDoctrineBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;
use FL\GmailBundle\Model\GmailMessage as BaseGmailMessage;
use FL\GmailBundle\Model\GmailMessageInterface;
use FL\GmailBundle\Model\GmailLabelInterface;

/**
 * Stores the relevant fields of Gmail Message, including its labels.
 * Most sql columns are of type text because we never know what an email might contain.
 *
 * @ORM\MappedSuperclass
 */
class GmailMessage extends BaseGmailMessage implements GmailMessageInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", nullable=false)
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", nullable=false, unique=true)
     *
     * @var string
     */
    protected $gmailId;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $threadId;

    /**
     * Not being used for anything at the moment.
     * Nevertheless, since each message has a unique historyId for its corresponding userId,
     * this historyId can be useful in the event that the latest historyId is not available elsewhere.
     * In this case, the latest historyId is simply the historyId with the largest value.
     *
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $historyId;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $userId;

    /**
     * The default column name `to` will cause an SQL syntax error.
     *
     * When there are A LOT of recipients, type string might not be big enough.
     *
     * @ORM\Column(name="to_", type="text", nullable=false)
     *
     * @var string
     */
    protected $to;

    /**
     * When there are A LOT of recipients, type string might not be big enough.
     *
     * @ORM\Column(type="text", nullable=false)
     *
     * @var string
     */
    protected $toCanonical;

    /**
     * The default column name `from` will cause an SQL syntax error.
     *
     * @ORM\Column(name="from_", type="text", nullable=false)
     *
     * @var string
     */
    protected $from;

    /**
     * @ORM\Column(type="text", nullable=false)
     *
     * @var string
     */
    protected $fromCanonical;

    /**
     * @ORM\Column(type="datetimetz", nullable=false)
     *
     * @var \DateTimeInterface
     */
    protected $sentAt;

    /**
     * @ORM\Column(type="text", nullable=false)
     *
     * @var string
     */
    protected $subject;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="GmailLabel", cascade={"persist", "detach"})
     */
    protected $labels;

    /**
     * @ORM\Column(type="text", nullable=false)
     *
     * @var string
     */
    protected $snippet;

    /**
     * @ORM\Column(type="text", nullable=false)
     *
     * @var string
     */
    protected $bodyPlainText;

    /**
     * @ORM\Column(type="text", nullable=false)
     *
     * @var string
     */
    protected $bodyHtml;

    /**
     * @ORM\Column(type="string", nullable=false)
     *
     * @var string
     */
    protected $domain;

    /**
     *  @see SyncSetting::$userIdsCurrentlyFlagged
     *
     * @var bool
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $flagged = false;

    public function __construct()
    {
        $this->labels = new ArrayCollection();
    }

    /**
     * Set Gmail Message ID.
     *
     * @param int $id
     *
     * @return $this
     */
    public function setId(int $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get Gmail Message ID.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function addLabel(GmailLabelInterface $label): GmailMessageInterface
    {
        $this->labels->add($label);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabels(): \Traversable
    {
        return $this->labels;
    }

    /**
     * {@inheritdoc}
     */
    public function removeLabel(GmailLabelInterface $label): GmailMessageInterface
    {
        $this->labels->removeElement($label);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function clearLabels(): GmailMessageInterface
    {
        $this->labels = new ArrayCollection();

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabelByName(string $name)
    {
        $criteria = Criteria::create();
        $criteria->where(Criteria::expr()->eq('name', $name));

        $labels = $this->labels->matching($criteria);

        if (
            ($labels->count() > 0) &&
            ($labels->first() instanceof GmailLabelInterface)
        ) {
            return $labels->first();
        }

        return;
    }

    /**
     * @return bool
     */
    public function isFlagged(): bool
    {
        return $this->flagged;
    }

    /**
     * @param bool $flagged
     */
    public function setFlagged(bool $flagged)
    {
        $this->flagged = $flagged;
    }
}
