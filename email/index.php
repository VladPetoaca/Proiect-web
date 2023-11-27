<form method="post" action="send.php">
    <label for="name">Name:</label>
    <input type="text" name="name" id="name" required> <br />

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required> <br />

    <label for="subject">Subject:</label>
    <input type="text" name="subject" id="subject" required> <br />

    <label for="msg">Message:</label>
    <textarea name="msg" id="msg" required></textarea>

    <button type="submit" name="send_message_btn">Send</button>
</form>