<?php

class Total
{
    private $total;

    /**
     * @return mixed
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * @param mixed total
     * @return Total
     */
    public function setTotal($total)
    {
        $total=Converter::formatToNumber($total);
        $this->total = $total;
        return $this;
    }



}