<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * user
 * @UniqueEntity("username",message="Ce pseudo n'est pas dispo")
 * @UniqueEntity("email",message="Cet email est dÃŠjÃ  inscrit ici")
 * @ORM\Table(name="app_user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\userRepository")
 */
class app_user implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @Assert\Length(
     *     min=4,
     *     max=30
     * )
     * @Assert\Regex("/^[a-zA-Z0-9_-]+$/", message="Votre pseudo doit matcher avec notre regex")
     * @Assert\NotBlank(message="Veuillez choisir un pseudo")
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     */
    private $username;

    /**
     * @var string
     * @Assert\Email()
     * @Assert\NotBlank()
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     */
    private $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\length(
     *     min=8,
     *     max=4000
     * )
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\critique", mappedBy="users")
     */
    private $critiques;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return user
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return user
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return user
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->critiques = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add critique
     *
     * @param \AppBundle\Entity\critique $critique
     *
     * @return user
     */
    public function addCritique(\AppBundle\Entity\critique $critique)
    {
        $this->critiques[] = $critique;

        return $this;
    }

    /**
     * Remove critique
     *
     * @param \AppBundle\Entity\critique $critique
     */
    public function removeCritique(\AppBundle\Entity\critique $critique)
    {
        $this->critiques->removeElement($critique);
    }

    /**
     * Get critiques
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCritiques()
    {
        return $this->critiques;
    }

    public function getRoles()
    {
        return ["ROLE_USER"];
    }

    public function getSalt()
    {
        return null;}

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }


}
