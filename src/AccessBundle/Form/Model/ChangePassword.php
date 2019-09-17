<?php

namespace AccessBundle\Form\Model;


use Symfony\Component\Security\Core\Validator\Constraints as SecurityAssert;
use Symfony\Component\Validator\Constraints as Assert;

class ChangePassword
{
    /**
     * @SecurityAssert\UserPassword(
     *     message = "Contraseña incorrecta"
     * )
     */
    protected $oldPassword;

    /**
     * @Assert\Length(
     *     min = 6,
     *     minMessage = "debe tener al menos 6 caractreres de largo"
     * )
     */
    protected $newPassword;


    public function setOldPassword( $oldPassword )
    {
        $this->oldPassword = $oldPassword;
        return $this;
    }

    public function getOldPassword()
    {
        return $this->oldPassword;
    }

    public function setNewPassword( $newPassword )
    {
        $this->newPassword = $newPassword;
        return $this;
    }

    public function getNewPassword()
    {
        return $this->newPassword;
    }

}
