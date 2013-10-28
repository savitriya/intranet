<?php
namespace IntranetUtils;

use Zend\Authentication\Adapter\AdapterInterface as AdapterInterface;
use Zend\Authentication\Result as ZendAuthResult;
use Zend\Authentication\Adapter\Exception\ExceptionInterface as ExceptionInterface;
use Doctrine\ORM\EntityManager as EntityManager;

class AuthAdapter implements AdapterInterface{
	/**
	 * The Entity/Classname which holds authentication data
	 * @var string
	 */
	private $authEntityName;

	/**
	 * The Field/Variable name which represents identity e.g. username
	 * @var string
	 */
	private $authIdentityField;
	 
	/**
	 * The Field/Variable name which represents credential e.g. password
	 * @var string
	 */
	private $authCredentialField;
	 
	/**
	 * The identity to be checked
	 * @var string
	 */
	private $identity;
	 
	/**
	 * The credentials to be checked
	 * @var string
	 */
	private $credential;
	

	/**
	 * Instance of an EntityManager
	 * @var Doctrine\ORM\EntityManager
	 */
	private $entityManager;
	 
	/**
	 * Constructor.
	 */
	public function __construct(DoctrineORMEntityManager $em=null, $authEntityName=null, $authIdentityField=null,
			$authCredentialField=null, $identity=null, $credential=null)
	{
		$this->authEntityName = $authEntityName;
		$this->authIdentityField = $authIdentityField;
		$this->authCredentialField = $authCredentialField;
		$this->identity = $identity;
		$this->credential = $credential;
		$this->authIsActiveField = "isactive";
		$this->entityManager = $em;
	}
	 
	/**
	 * (non-PHPdoc)
	 * @see Zend_Auth_Adapter_Interface::authenticate()
	 */
	public function authenticate()
	{
		try{
			$authEntity = $this->entityManager->getRepository($this->authEntityName)
			->findOneBy(array(
					$this->authIdentityField => $this->identity,
					$this->authCredentialField => $this->credential,
					$this->authIsActiveField => 1
			));
			 
			if($authEntity !== null) {
				return new ZendAuthResult(ZendAuthResult::SUCCESS,$authEntity);
			} else {
				return new ZendAuthResult(ZendAuthResult::FAILURE_CREDENTIAL_INVALID, null);
			}
		}
		catch(Exception $e){
			throw new Zend\Authentication\Adapter\Exception($e->getMessage());
		}
	}
	 
	/**
	 * @return string
	 */
	public function getAuthEntityName()
	{
		return $this->authEntityName;
	}
	 
	/**
	 * @return string
	 */
	public function getAuthIdentityField()
	{
		return $this->authIdentityField;
	}
	 
	/**
	 * @return string
	 */
	public function getAuthCredentialField()
	{
		return $this->authCredentialField;
	}
	 
	/**
	 * @return string
	 */
	public function getIdentity()
	{
		return $this->identity;
	}
	 
	/**
	 * @return string
	 * Enter description here ...
	 */
	public function getCredential()
	{
		return $this->credential;
	}

	/**
	 * @param string $authEntityName
	 * @return Utils\AuthAdapter
	 */
	public function setAuthEntityName($authEntityName)
	{
		$this->authEntityName = $authEntityName;
		return $this;
	}
	 
	/**
	 * @param string $authIdentityField
	 * @return Utils\AuthAdapter
	 */
	public function setAuthIdentityField($authIdentityField)
	{
		$this->authIdentityField = $authIdentityField;
		return $this;
	}
	 
	/**
	 * @param string $authCredentialField
	 * @return Utils\AuthAdapter
	 */
	public function setAuthCredentialField($authCredentialField)
	{
		$this->authCredentialField = $authCredentialField;
		return $this;
	}
	 
	/**
	 * @param string $identity
	 * @return Utils\AuthAdapter
	 */
	public function setIdentity($identity)
	{
		$this->identity = $identity;
		return $this;
	}
	 
	/**
	 * @param string $credential
	 * @return Utils\AuthAdapter
	 */
	public function setCredential($credential)
	{
		$this->credential = md5($credential);
		return $this;
	}
	 
	/**
	 * @param Doctrine\ORM\EntityManager $em
	 * @return Utils\AuthAdapter
	 */
	public function setEntityManager(EntityManager $em)
	{
		$this->entityManager = $em;
		return $this;
	}
}