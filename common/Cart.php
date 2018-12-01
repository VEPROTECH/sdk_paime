<?php

class Cart
{
    private $products;
    private $id;

    public function getProducts()
    {
        return $this->products;
    }


    public function addProduct(Item $product)
    {
        $this->products[] = $product;
    }


    public function getId()
    {
        return $this->id;
    }


    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Returns the sum off all products prices
     * array_reduce docs: http://php.net/manual/en/function.array-reduce.php
     */
    public function total(){
        return array_reduce($this->getProducts(), function($carry, $product){
            return $carry + $product->getPrice();
        });
    }
}