<?php

namespace FL\GmailDoctrineBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use FL\GmailBundle\Services\GoogleClientStatus;
use FL\GmailDoctrineBundle\Entity\SyncSetting;

/**
 * Class GoogleClientStatusWrapper
 * @package FL\GmailDoctrineBundle\Services
 *
 * This class provides a wrapper to interact with
 * @see \FL\GmailBundle\Services\GoogleClientStatus
 */
class GoogleClientStatusWrapper
{
    /**
     * @var GoogleClientStatus
     */
    private $clientStatus;

    /**
     * @var EntityRepository
     */
    private $syncSettingRepository;

    /**
     * @param GoogleClientStatus $clientStatus
     * @param EntityManager $entityManager
     * @param string $syncSettingClass
     */
    public function __construct(
        GoogleClientStatus $clientStatus,
        EntityManager $entityManager,
        string $syncSettingClass
    ) {
        $this->clientStatus = $clientStatus;
        $this->syncSettingRepository = $entityManager->getRepository($syncSettingClass);
    }

    /**
     * @param string $domain
     * @return bool
     */
    public function isSetupForDomain(string $domain)
    {
        $syncSetting = $this->syncSettingRepository->findOneByDomain($domain);

        if (
            ($this->clientStatus->isAuthenticated() === true) &&
            ($syncSetting instanceof SyncSetting)
        ) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isSetupForAtLeastOneDomain()
    {
        $syncSetting = $this->syncSettingRepository->findOneBy([]);

        if (
            ($this->clientStatus->isAuthenticated() === true) &&
            ($syncSetting instanceof SyncSetting)
        ) {
            return true;
        }
        return false;

    }

}
