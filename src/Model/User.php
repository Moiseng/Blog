<?php

namespace App\Model;

final class User
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;


    /**
     * Récupère l'id de l'utilisateur
     *
     * @return int|null
     */
    public function getID(): ?int
    {
        return $this->id;
    }

    public function setID(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Récupère la valeur de l'utilisateur
     *
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Définie la vlaeur de l'username
     *
     * @param string $username
     * @return User
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    /**
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

}
