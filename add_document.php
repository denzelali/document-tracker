<?php include("config/db.php"); ?>
<?php include("includes/header.php"); ?>

<div class="container mx-auto max-w-4xl px-4 py-6">
  <!-- Official Header -->
  <div class="bg-blue-900 text-white p-6 mb-6">
    <h2 class="text-2xl font-bold text-center">DOCUMENT MANAGEMENT SYSTEM</h2>
    <p class="text-center text-blue-100 mt-2">Add New Document</p>
  </div>

  <!-- Form Section -->
  <div class="bg-white border-2 border-gray-300 p-8">
    <form method="POST" action="" class="space-y-6">
      
      <!-- Document Information Section -->
      <div class="border-b-2 border-gray-200 pb-4 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">DOCUMENT INFORMATION</h3>
      </div>

      <!-- Title Field -->
      <div class="mb-4">
        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">
          Document Title *
        </label>
        <input type="text" name="title" 
               class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50" 
               required>
      </div>

      <!-- Description Field -->
      <div class="mb-4">
        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">
          Description
        </label>
        <textarea name="description" rows="4"
                  class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50 resize-vertical"></textarea>
      </div>

      <!-- Two Column Layout -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Category -->
        <div class="mb-4">
          <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">
            Category
          </label>
          <input type="text" name="category" 
                 class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
        </div>

        <!-- Status -->
        <div class="mb-4">
          <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">
            Status
          </label>
          <select name="status" 
                  class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
            <option value="Pending">Pending</option>
            <option value="Approved">Approved</option>
            <option value="In Review">In Review</option>
            <option value="Rejected">Rejected</option>
          </select>
        </div>
      </div>

      <!-- Location Field -->
      <div class="mb-4">
        <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">
          Location
        </label>
        <input type="text" name="location" 
               class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
      </div>

      <!-- Two Column Layout for Submission Details -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Submitted By -->
        <div class="mb-4">
          <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">
            Submitted By
          </label>
          <input type="text" name="submitted_by" 
                 class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
        </div>

        <!-- Submitted Date -->
        <div class="mb-4">
          <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">
            Submitted Date
          </label>
          <input type="datetime-local" name="submitted_date" 
                 class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
        </div>
      </div>

      <!-- Submit Section -->
      <div class="border-t-2 border-gray-200 pt-6 mt-8">
        <div class="text-center">
          <button type="submit" 
                  class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 px-8 border-2 border-blue-900 uppercase tracking-wide">
            SAVE DOCUMENT
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- Status Messages -->
  <?php
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $status = $_POST['status'];

    $sql = "INSERT INTO documents (title, description, category, status)
            VALUES ('$title', '$description', '$category', '$status')";

    if ($conn->query($sql) === TRUE) {
      echo '<div class="mt-6 bg-green-100 border-2 border-green-600 p-4">
              <p class="text-green-800 font-bold text-center">✓ DOCUMENT ADDED SUCCESSFULLY</p>
            </div>';
    } else {
      echo '<div class="mt-6 bg-red-100 border-2 border-red-600 p-4">
              <p class="text-red-800 font-bold text-center">✗ ERROR: ' . $conn->error . '</p>
            </div>';
    }
  }
  ?>

  <!-- Footer Information -->
  <div class="mt-8 text-center text-gray-600 text-sm">
    <p>* Required fields must be completed</p>
    <p>For assistance, contact your system administrator</p>
  </div>
</div>

<style>
/* Government Office Styling */
body {
  font-family: 'Arial', sans-serif;
  background-color: #f8f9fa;
}

/* Print-friendly styles */
@media print {
  .no-print {
    display: none;
  }
  
  body {
    background: white;
  }
}

/* Accessibility improvements */
input:focus,
textarea:focus,
select:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.3);
}

/* High contrast for better readability */
label {
  color: #1f2937;
}

/* Consistent spacing */
.container {
  line-height: 1.6;
}
</style>

<?php include("includes/footer.php"); ?>