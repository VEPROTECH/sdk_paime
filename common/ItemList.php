<?php

class ItemList extends Item
{

    private $items;
    /**
     * List of items.
     *
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param Item[] $items
     *
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * Append Items to the list.
     *
     * @param Item[] $item
     * @return $this
     */
    public function  addItem($item)
    {
        if (!$this->getItems()) {
            return $this->setItems(array($item));
        } else {
            return $this->setItems(
                array_merge($this->getItems(), array($item))
            );
        }
    }


}