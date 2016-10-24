<?php

namespace FL\GmailDoctrineBundle\Storage;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use FL\GmailBundle\Storage\CredentialsStorageInterface;
use FL\GmailDoctrineBundle\Entity\Credentials;

/**
 * Class CredentialsStorage
 * @package FL\GmailDoctrineBundle\Storage
 */
class CredentialsStorage implements CredentialsStorageInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $credentialsRepository;

    /**
     * CredentialsStorage constructor.
     * @param EntityManagerInterface $entityManager
     * @param string $credentialsClass
     */
    public function __construct(EntityManagerInterface $entityManager, string $credentialsClass)
    {
        $this->entityManager = $entityManager;
        $this->credentialsRepository = $entityManager->getRepository($credentialsClass);
    }

    /**
     * @inheritdoc
     */
    public function persistTokenArray(array $tokenArray)
    {
        $credentials = $this->credentialsRepository->findOneBy([]);
        if (!($credentials instanceof Credentials)) {
            $credentials = new Credentials();
        }

        $credentials->setTokenArray($tokenArray);
        $this->entityManager->persist($credentials);
        $this->entityManager->flush();
    }

    /**
     * @inheritdoc
     */
    public function getTokenArray()
    {
        $credentials = $this->credentialsRepository->findOneBy([]);
        if (!($credentials instanceof Credentials)) {
            return null;
        }

        return $credentials->getTokenArray();
    }

    /**
     * @inheritdoc
     */
    public function persistAuthCode(string $authCode)
    {
        $credentials = $this->credentialsRepository->findOneBy([]);
        if (!($credentials instanceof Credentials)) {
            $credentials = new Credentials();
        }

        $credentials->setAuthCode($authCode);
        $this->entityManager->persist($credentials);
        $this->entityManager->flush();
    }

    /**
     * @inheritdoc
     */
    public function getAuthCode()
    {
        $credentials = $this->credentialsRepository->findOneBy([]);
        if (!($credentials instanceof Credentials)) {
            return null;
        }

        return $credentials->getAuthCode();
    }

    /**
     * @inheritdoc
     */
    public function deleteAuthCode()
    {
        $credentials = $this->credentialsRepository->findOneBy([]);
        if (!($credentials instanceof Credentials)) {
            $credentials = new Credentials();
        }

        $credentials->setAuthCode(null);
        $this->entityManager->persist($credentials);
        $this->entityManager->flush();
    }
}