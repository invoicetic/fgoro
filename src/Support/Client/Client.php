<?php

namespace Invoicetic\Fgoro\Support\Client;

class Client
{
    protected $Tip;

    protected $IdExtern;
    protected $Denumire;

    protected $CodUnic;

    protected $NrRegCom;

    protected $Email;

    protected $Telefon;

    protected $Tara;

    protected $Judet;

    protected $Localitate;

    protected $Adresa;

    protected $ContBancar;

    protected $PlatitorTVA;

    /**
     * @return mixed
     */
    public function getTip()
    {
        return $this->Tip;
    }

    /**
     * @param mixed $Tip
     */
    public function setTip($Tip): void
    {
        $this->Tip = $Tip;
    }

    /**
     * @return mixed
     */
    public function getIdExtern()
    {
        return $this->IdExtern;
    }

    /**
     * @param mixed $IdExtern
     */
    public function setIdExtern($IdExtern): void
    {
        $this->IdExtern = $IdExtern;
    }

    /**
     * @return mixed
     */
    public function getDenumire()
    {
        return $this->Denumire;
    }

    /**
     * @param mixed $Denumire
     */
    public function setDenumire($Denumire): void
    {
        $this->Denumire = $Denumire;
    }

    /**
     * @return mixed
     */
    public function getCodUnic()
    {
        return $this->CodUnic;
    }

    /**
     * @param mixed $CodUnic
     */
    public function setCodUnic($CodUnic): void
    {
        $this->CodUnic = $CodUnic;
    }

    /**
     * @return mixed
     */
    public function getNrRegCom()
    {
        return $this->NrRegCom;
    }

    /**
     * @param mixed $NrRegCom
     */
    public function setNrRegCom($NrRegCom): void
    {
        $this->NrRegCom = $NrRegCom;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->Email;
    }

    /**
     * @param mixed $Email
     */
    public function setEmail($Email): void
    {
        $this->Email = $Email;
    }

    /**
     * @return mixed
     */
    public function getTelefon()
    {
        return $this->Telefon;
    }

    /**
     * @param mixed $Telefon
     */
    public function setTelefon($Telefon): void
    {
        $this->Telefon = $Telefon;
    }

    /**
     * @return mixed
     */
    public function getTara()
    {
        return $this->Tara;
    }

    /**
     * @param mixed $Tara
     */
    public function setTara($Tara): void
    {
        $this->Tara = $Tara;
    }

    /**
     * @return mixed
     */
    public function getJudet()
    {
        return $this->Judet;
    }

    /**
     * @param mixed $Judet
     */
    public function setJudet($Judet): void
    {
        $this->Judet = $Judet;
    }

    /**
     * @return mixed
     */
    public function getLocalitate()
    {
        return $this->Localitate;
    }

    /**
     * @param mixed $Localitate
     */
    public function setLocalitate($Localitate): void
    {
        $this->Localitate = $Localitate;
    }

    /**
     * @return mixed
     */
    public function getAdresa()
    {
        return $this->Adresa;
    }

    /**
     * @param mixed $Adresa
     */
    public function setAdresa($Adresa): void
    {
        $this->Adresa = $Adresa;
    }

    /**
     * @return mixed
     */
    public function getContBancar()
    {
        return $this->ContBancar;
    }

    /**
     * @param mixed $ContBancar
     */
    public function setContBancar($ContBancar): void
    {
        $this->ContBancar = $ContBancar;
    }

    /**
     * @return mixed
     */
    public function getPlatitorTVA()
    {
        return $this->PlatitorTVA;
    }

    /**
     * @param mixed $PlatitorTVA
     */
    public function setPlatitorTVA($PlatitorTVA): void
    {
        $this->PlatitorTVA = $PlatitorTVA;
    }


    public function toArray(): array
    {
        $data = [
            'Tip' => $this->Tip,
            'Denumire' => $this->Denumire,
            'CodUnic' => $this->CodUnic,
            'NrRegCom' => $this->NrRegCom,
            'Email' => $this->Email,
            'Telefon' => $this->Telefon,
            'Tara' => $this->Tara,
            'Judet' => $this->Judet,
            'Localitate' => $this->Localitate,
            'Adresa' => $this->Adresa,
        ];
        if ($this->IdExtern) {
            $data['IdExtern'] = $this->IdExtern;
        }
        return $data;
    }
}
