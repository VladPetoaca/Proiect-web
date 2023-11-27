<?php
class ShoppingCart
{
    private $cartItems;

    public function __construct()
    {
        // Inițializare coș de cumpărături
        $this->cartItems = [];
    }

    public function getCartItems()
    {
        return $this->cartItems;
    }

    public function addToCart($eventID, $quantity)
    {
        // Adaugă biletele în coș
        $this->cartItems[$eventID] = [
            'eventID' => $eventID,
            'quantity' => $quantity,
        ];
    }

    public function removeFromCart($eventID)
    {
        // Elimină biletele din coș
        unset($this->cartItems[$eventID]);
    }

    public function clearCart()
    {
        // Golește coșul de cumpărături
        $this->cartItems = [];
    }
}
?>
