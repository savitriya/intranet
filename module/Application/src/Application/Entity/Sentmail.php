<?php

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * A music album.
 *
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Application\Repository\SentmailRepository")
 * @ORM\Table(name="sentmails")
 * @property int $id
 * @property text $parent_id
 * @property text $table_name
 * @property int $type_id
 * @property text $mail_to
 * @property int $cc
 * @property int $bcc
 * @property int $mail_from
 * @property text $content
 * @property int $created_date_time
 * @property int $sent_date_time
 * @property int $user_id
 */

class Sentmail
{
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer");
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected  $id;
	
	/**
	 * @ORM\Column(type="integer",nullable=true)
	 */
	protected  $parent_id;
	
	/**
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected  $table_name;
	
	/**
	 * @ORM\Column(type="integer");
	 */
	protected  $type_id;
	
	/**
	 * @ORM\Column(type="string")
	 */
	protected  $mail_to;
	
	/**
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected  $cc;

	/**
	 * @ORM\Column(type="string",nullable=true)
	 */
	protected  $bcc;

	
	/**
	 * @ORM\Column(type="string")
	 */
	protected  $mail_from;
	
	/**
	 * @ORM\Column(type="text")
	 */
	protected  $content;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected  $created_date_time;
	
	/**
	 * @ORM\Column(type="integer")
	 */
	protected $sent_date_time;
	
	/**
	 * @ORM\Column(type="integer");
	 */
	protected  $user_id;

/*	
	public function __construct() {
	}
	
	/**
	 * Magic getter to expose protected properties.
	 *
	 * @param string $property
	 * @return mixed
	 
	
	public function __get($property)
	{
		return $this->$property;
	}
	/**
	 * Magic setter to save protected properties.
	 *
	 * @param string $property
	 * @param mixed $value
	 
	public function __set($property, $value)
	{
		$this->$property = $value;
	}
*/
	/**
	 * Returns the id
	 *
	 * @access public
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * Sets the parent_id
	 *
	 * @param int $parent_id
	 * @access public
	 * @return Sentmail
	 */
	public function setParentId($parent_id)
	{
		$this->parent_id = $parent_id;
		return $this;
	}
	
	/**
	 * Returns the parent_id
	 *
	 * @access public
	 * @return int
	 */
	public function getParentId()
	{
		return $this->parent_id;
	}
	
	/**
	 * Sets the table_name
	 *
	 * @param string $table_name
	 * @access public
	 * @return Sentmail
	 */
	public function setTableName($table_name)
	{
		$this->table_name = $table_name;
		return $this;
	}

	/**
	 * Returns the table_name
	 *
	 * @access public
	 * @return string
	 */
	public function getTableName()
	{
		return $this->table_name;
	}
	
	/**
	 * Sets the type_id
	 *
	 * @param int $type_id
	 * @access public
	 * @return Sentmail
	 */
	public function setTypeId($type_id)
	{
		$this->type_id = $type_id;
		return $this;
	}

	/**
	 * Returns the type_id
	 *
	 * @access public
	 * @return int
	 */
	public function getTypeId()
	{
		return $this->type_id;
	}
	
	/**
	 * Sets the mail_to
	 *
	 * @param string $mail_to
	 * @access public
	 * @return Sentmail
	 */
	public function setMailTo($mail_to)
	{
		$this->mail_to = $mail_to;
		return $this;
	}
	
	/**
	 * Returns the mail_to
	 *
	 * @access public
	 * @return string
	 */
	public function getMailTo()
	{
		return $this->mail_to;
	}
	
	/**
	 * Sets the cc
	 *
	 * @param string $cc
	 * @access public
	 * @return Sentmail
	 */
	public function setCc($cc)
	{
		$this->cc = $cc;
		return $this;
	}
	
	/**
	 * Returns the cc
	 *
	 * @access public
	 * @return string
	 */
	public function getCc()
	{
		return $this->cc;
	}
	
	/**
	 * Sets the bcc
	 *
	 * @param string $bcc
	 * @access public
	 * @return Sentmail
	 */
	public function setBcc($bcc)
	{
		$this->bcc = $bcc;
		return $this;
	}
	
	/**
	 * Returns the bcc
	 *
	 * @access public
	 * @return string
	 */
	public function getBcc()
	{
		return $this->bcc;
	}
	
	/**
	 * Sets the mail_from
	 *
	 * @param string $mail_from
	 * @access public
	 * @return Sentmail
	 */
	public function setMailFrom($mail_from)
	{
		$this->mail_from = $mail_from;
		return $this;
	}

	/**
	 * Returns the mail_from
	 *
	 * @access public
	 * @return string
	 */
	public function getMailFrom()
	{
		return $this->mail_from;
	}
	
	/**
	 * Sets the content
	 *
	 * @param string $content
	 * @access public
	 * @return Sentmail
	 */
	public function setContent($content)
	{
		$this->content = $content;
		return $this;
	}

	/**
	 * Returns the content
	 *
	 * @access public
	 * @return string
	 */
	public function getContent()
	{
		return $this->content;
	}
	
	/**
	 * Sets the createddatetime
	 *
	 * @param int $createddatetime
	 * @access public
	 * @return Sentmail
	 */
	public function setCreatedDatetTime($createddatetime)
	{
		$this->created_date_time = $createddatetime;
		return $this;
	}
	
	/**
	 * Returns the created_date_time
	 *
	 * @access public
	 * @return int
	 */
	public function getCreatedDatetTime()
	{
		return $this->created_date_time;
	}
	
	/**
	 * Sets the sent_date_time
	 *
	 * @param int $sentdatetime
	 * @access public
	 * @return Sentmail
	 */
	public function setSentDateTime($sentdatetime)
	{
		$this->sent_date_time = $sentdatetime;
		return $this;
	}
	
	/**
	 * Returns the sent_date_time
	 *
	 * @access public
	 * @return int
	 */
	public function getSentDateTime()
	{
		return $this->sent_date_time;
	}
	
	/**
	 * Sets the user_id
	 *
	 * @param int $user_id
	 * @access public
	 * @return Sentmail
	 */
	public function setUserId($user_id)
	{
		$this->user_id = $user_id;
		return $this;
	}
	
	/**
	 * Returns the user_id
	 *
	 * @access public
	 * @return int
	 */
	public function getUserId()
	{
		return $this->user_id;
	}
	
}