<?php include("config/db.php"); ?>
<?php include("includes/header.php"); ?>

<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM documents WHERE doc_id = $id";
    $result = $conn->query($sql);
    $doc = $result->fetch_assoc();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    $sql = "UPDATE documents 
            SET title='$title', description='$description', category='$category', status='$status', updated_at=NOW()
            WHERE doc_id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<p class='mt-4 text-green-600 font-semibold'>✅ Document updated successfully!</p>";
    } else {
        echo "<p class='mt-4 text-red-600 font-semibold'>❌ Error: " . $conn->error . "</p>";
    }
}
?>

<h2 class="text-2xl font-bold mb-4">Edit Document</h2>

<form method="POST" class="bg-white shadow rounded-lg p-6 space-y-4">
  <div>
    <label class="block font-semibold">Title</label>
    <input type="text" name="title" value="<?php echo $doc['title']; ?>" class="w-full p-2 border rounded" required>
  </div>
  <div>
    <label class="block font-semibold">Description</label>
    <textarea name="description" class="w-full p-2 border rounded"><?php echo $doc['description']; ?></textarea>
  </div>
  <div>
    <label class="block font-semibold">Category</label>
    <input type="text" name="category" value="<?php echo $doc['category']; ?>" class="w-full p-2 border rounded">
  </div>
  <div>
    <label class="block font-semibold">Status</label>
    <select name="status" class="w-full p-2 border rounded">
      <option value="Pending" <?php if($doc['status']=="Pending") echo "selected"; ?>>Pending</option>
      <option value="Approved" <?php if($doc['status']=="Approved") echo "selected"; ?>>Approved</option>
      <option value="In Review" <?php if($doc['status']=="In Review") echo "selected"; ?>>In Review</option>
      <option value="Rejected" <?php if($doc['status']=="Rejected") echo "selected"; ?>>Rejected</option>
    </select>
  </div>
  <label class="block mb-2">Location</label>
<input type="text" name="location" class="border rounded px-3 py-2 w-full mb-4">

<label class="block mb-2">Submitted By</label>
<input type="text" name="submitted_by" class="border rounded px-3 py-2 w-full mb-4">

<label class="block mb-2">Submitted Date</label>
<input type="datetime-local" name="submitted_date" class="border rounded px-3 py-2 w-full mb-4">
  <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
    Update
  </button>
</form>

<?php include("includes/footer.php"); ?>
