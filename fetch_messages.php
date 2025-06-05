<?php
$conn = new mysqli("localhost", "root", "", "learning");

$sql = "SELECT * FROM contact_messages ORDER BY submitted_at DESC";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
  $is_read_class = $row['is_read'] ? '' : 'unread';
  echo "<tr class='$is_read_class'>";
  echo "<td>" . htmlspecialchars($row['name']) . "</td>";
  echo "<td>" . htmlspecialchars($row['email']) . "</td>";
  echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
  echo "<td>" . nl2br(htmlspecialchars($row['message'])) . "</td>";
  echo "<td>" . $row['submitted_at'] . "</td>";
  echo "<td class='actions'>
          <button onclick='toggleReadStatus({$row['id']}, \"" . ($row['is_read'] ? "unread" : "read") . "\")'>" . ($row['is_read'] ? "Mark Unread" : "Mark Read") . "</button>
          <button onclick='showReplyForm({$row['id']})'>Reply</button>
          <button onclick='deleteMessage({$row['id']})'>Delete</button>
        </td>
        </tr>
        <tr>
          <td colspan='6'>
            <form id='reply-form-{$row['id']}' class='reply-form' onsubmit='event.preventDefault(); sendReply({$row['id']});'>
              <input type='hidden' name='email' value='" . htmlspecialchars($row['email']) . "'>
              <label>Reply Message:</label><br>
              <textarea name='reply_message' rows='3' cols='60' required></textarea><br>
              <button type='submit'>Send Reply</button>
            </form>
          </td>
        </tr>";
}
$conn->close();
?>
