<?php include("config/db.php"); ?>
<?php include("includes/header.php"); ?>

<h2 class="text-2xl font-bold mb-4">All Documents</h2>

<div class="overflow-x-auto bg-white rounded-lg shadow">
  <table class="min-w-full text-sm text-left text-gray-700">
  <thead class="bg-gray-200 text-gray-700 uppercase text-xs">
  <tr>
    <th class="px-4 py-2">ID</th>
    <th class="px-4 py-2">Title</th>
    <th class="px-4 py-2">Description</th>
    <th class="px-4 py-2">Category</th>
    <th class="px-4 py-2">Location</th>
    <th class="px-4 py-2">Submitted By</th>
    <th class="px-4 py-2">Submitted Date</th>
    <th class="px-4 py-2">Status</th>
    <th class="px-4 py-2">Created</th>
    <th class="px-4 py-2">Actions</th>
  </tr>
</thead>
<tbody>
  <?php
  $sql = "SELECT * FROM documents ORDER BY created_at DESC";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo "<tr class='border-b hover:bg-gray-50'>
        <td class='px-4 py-2'>{$row['doc_id']}</td>
        <td class='px-4 py-2 font-medium'>{$row['title']}</td>
        <td class='px-4 py-2 font-medium'>{$row['description']}</td>
        <td class='px-4 py-2'>{$row['category']}</td>
        <td class='px-4 py-2'>{$row['location']}</td>
        <td class='px-4 py-2'>{$row['submitted_by']}</td>
        <td class='px-4 py-2'>{$row['submitted_date']}</td>
        <td class='px-4 py-2'>
          <span class='px-2 py-1 rounded text-white " .
          ($row['status'] == 'Approved' ? 'bg-green-500' :
           ($row['status'] == 'In Review' ? 'bg-yellow-500' :
           ($row['status'] == 'Rejected' ? 'bg-red-500' : 'bg-gray-500'))) .
          "'>{$row['status']}</span>
        </td>
        <td class='px-4 py-2'>{$row['created_at']}</td>
        <td class='px-4 py-2 space-x-2'>
          <a class='bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition' 
             href='edit_document.php?id={$row['doc_id']}'>Edit</a>
          <a class='bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition' 
             href='delete_document.php?id={$row['doc_id']}' 
             onclick='return confirm(\"Are you sure you want to delete this document?\");'>Delete</a>
        </td>
      </tr>";
    }
  } else {
    echo "<tr><td colspan='9' class='px-4 py-2 text-center text-gray-500'>No documents found.</td></tr>";
  }
  ?>
</tbody>

  </table>
</div>

<?php include("includes/footer.php"); ?>
