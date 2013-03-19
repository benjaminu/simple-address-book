<?php

namespace Xtreem\AddressBookBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * AddressBook Entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="address_book")
 * @Gedmo\SoftDeleteable(fieldName="deleted")
 */
class AddressBook
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, name="first_name", nullable=false)
     * @Assert\Length(max = "100", maxMessage="Please enter a first name that contains less than 100 characters.")
     * @Assert\NotBlank(message="Please enter a valid first name.")
     * @Assert\Type(type="string")
     */
    protected $firstName;

    /**
     * @ORM\Column(type="string", length=100, name="last_name", nullable=false)
     * @Assert\Length(max = "100", maxMessage="Please enter a last name that contains less than 100 characters.")
     * @Assert\NotBlank(message="Please enter a valid last name.")
     * @Assert\Type(type="string")
     */
    protected $lastName;

    /**
     * @ORM\Column(type="string", length=32, name="line_1", nullable=false)
     * @Assert\Length(max = "32", maxMessage="The first line of your address should contain less than 32 characters.")
     * @Assert\NotBlank(message="Please provide a valid first address line.")
     * @Assert\Type(type="string")
     */
    protected $line1;

    /**
     * @ORM\Column(type="string", length=32, name="line_2", nullable=true)
     * @Assert\Length(max = "32", maxMessage="The second line of your address should contain less than 32 characters.")
     * @Assert\Type(type="string")
     */
    protected $line2;

    /**
     * @ORM\Column(type="string", length=32, name="city", nullable=false)
     * @Assert\Length(max = "32", maxMessage="Please enter a city that contains less than 32 characters.")
     * @Assert\NotBlank(message="Please provide a valid city.")
     * @Assert\Type(type="string")
     */
    protected $city;

    /**
     * @ORM\Column(type="string", length=32, name="post_code", nullable=false)
     * @Assert\Length(max = "32", maxMessage="Please enter a post code that contains less than 32 characters.")
     * @Assert\NotBlank(message="Please provide a valid post code.")
     * @Assert\Type(type="string")
     */
    protected $postCode;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", options={"format": "Y-m-d"})
     */
    protected $created;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @ORM\Column(name="deleted", type="datetime", nullable=true)
     */
    protected $deleted;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set first name.
     *
     * @param string $firstName
     *
     * @return AddressBook
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get first name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set last name.
     *
     * @param string $lastName
     *
     * @return AddressBook
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get last name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Get first and last name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->firstName.' '.$this->lastName;
    }

    /**
     * Set line 1.
     *
     * @param string $line1
     *
     * @return AddressBook
     */
    public function setLine1($line1)
    {
        $this->line1 = $line1;

        return $this;
    }

    /**
     * Get line 1.
     *
     * @return string
     */
    public function getLine1()
    {
        return $this->line1;
    }

    /**
     * Set line 2.
     *
     * @param string $line2
     *
     * @return AddressBook
     */
    public function setLine2($line2)
    {
        $this->line2 = $line2;

        return $this;
    }

    /**
     * Get line 2.
     *
     * @return string
     */
    public function getLine2()
    {
        return $this->line2;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return AddressBook
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set post code.
     *
     * @param string $postCode
     *
     * @return AddressBook
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;

        return $this;
    }

    /**
     * Get post code.
     *
     * @return string
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * Returns address.
     *
     * @return array
     */
    public function getContactAddress()
    {
        $address = array();

        $address[] = ucwords(strtolower($this->getLine1()));

        if ($this->getLine2()) {
            $address[] = ucwords(strtolower($this->getLine2()));
        }

        $address[] = ucwords(strtolower($this->getCity()));
        $address[] = strtoupper(strtolower($this->getPostCode()));

        return $address;
    }

    /**
     * Set created
     *
     * @param datetime $created
     *
     * @return Invoice
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return datetime
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param datetime $updated
     *
     * @return Invoice
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return datetime
     */
    public function getUpdated()
    {
        return $this->updated;
    }

    /**
     * Set deleted
     *
     * @param datetime $deleted
     *
     * @return Invoice
     */
    public function setDeleted($deleted)
    {
        $this->deleted = $deleted;

        return $this;
    }

    /**
     * Get deleted
     *
     * @return datetime
     */
    public function getDeleted()
    {
        return $this->deleted;
    }
}