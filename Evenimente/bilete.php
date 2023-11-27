<?php
include('ShoppingCart.php');
session_start();

// Inițializare coș de cumpărături
if (!isset($_SESSION['shoppingCart'])) {
    $_SESSION['shoppingCart'] = new ShoppingCart();
}

$shoppingCart = $_SESSION['shoppingCart'];

// Procesare adăugare în coș
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $eventID = $_POST['event_id'];
    $quantity = $_POST['quantity'];

    // Adaugă biletele în coș
    $shoppingCart->addToCart($eventID, $quantity);
}

// Procesare eliminare din coș
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_from_cart'])) {
    $eventID = $_POST['event_id'];

    // Elimină biletele din coș
    $shoppingCart->removeFromCart($eventID);
}

// Procesare golire coș
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_cart'])) {
    // Golește coșul de cumpărături
    $shoppingCart->clearCart();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evenimente</title>
    <style>
        /* Adăugați stilurile CSS aici pentru aspect mai frumos */
    </style>
</head>
<body>

<h2>Cumpara bilet</h2>

<!-- Afiseaza evenimente aici -->

<?php
// Exemplu de eveniment
$eventID = 1;
$eventPrice = 50.00;
?>

<div>
    <p>Preț: <?php echo $eventPrice; ?> RON</p>

    <form method="post" action="">
        <input type="hidden" name="event_id" value="<?php echo $eventID; ?>">
        <label for="quantity">Cantitate:</label>
        <input type="number" name="quantity" value="1" min="1">
        <button type="submit" name="add_to_cart">Adaugă în Coș</button>
    </form>
</div>

<!-- Afiseaza coșul de cumpărături aici -->

<h2>Coș de Cumpărături</h2>

<?php
$cartItems = $shoppingCart->getCartItems();

if (!empty($cartItems)) {
    foreach ($cartItems as $cartItem) {
        $eventID = $cartItem['eventID'];
        $quantity = $cartItem['quantity'];

        // Afiseaza detaliile din coș
        echo "<p>Eveniment ID: $eventID, Cantitate: $quantity</p>";

        // Formular pentru eliminare din coș
        echo "<form method='post' action=''>
                <input type='hidden' name='event_id' value='$eventID'>
                <button type='submit' name='remove_from_cart'>Elimină din Coș</button>
              </form>";
    }

    // Formular pentru golire coș
    echo "<form method='post' action=''>
            <button type='submit' name='clear_cart'>Golește Coșul</button>
          </form>";

    // Buton pentru a merge la pagina de plată
    echo "<form method='post' action='cos.php'>
            <button type='submit' name='procesare_plata'>Plătește acum</button>
          </form>";
} else {
    echo "<p>Coșul de cumpărături este gol.</p>";
}
?>

</body>
</html>