<?php

namespace Invoicetic\Fgoro\Support\Invoice;

class Invoice
{
    public const TYPE_INVOICE = 'Factura';

    protected string $TipFactura = self::TYPE_INVOICE;

    protected ?string $Serie = null;

    protected ?string $Numar = null;

    protected ?string $Valuta = null;


    protected $IdExtern;

    public function getTipFactura(): string
    {
        return $this->TipFactura;
    }

    public function setTipFactura(string $TipFactura): void
    {
        $this->TipFactura = $TipFactura;
    }

    public function getSerie(): ?string
    {
        return $this->Serie;
    }

    public function setSerie(?string $Serie): void
    {
        $this->Serie = $Serie;
    }

    public function getNumar(): ?string
    {
        return $this->Numar;
    }

    public function setNumar(?string $Numar): void
    {
        $this->Numar = $Numar;
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

    public function getValuta(): ?string
    {
        return $this->Valuta;
    }

    public function setValuta(?string $Valuta): void
    {
        $this->Valuta = $Valuta;
    }

    public function toArray(): array
    {
        $data = [
            'TipFactura' => $this->TipFactura,
            'Serie' => $this->Serie,
            'Numar' => $this->Numar,
            'Valuta' => $this->Valuta,
        ];
        if ($this->IdExtern) {
            $data['IdExtern'] = $this->IdExtern;
        }
        return $data;
    }
}
