<?php
include("config/db.php");
include("includes/header.php");

$locationsList = array(
    "ENDORSEMENT FOR FUEL CARD RELEASE",
    "ENDORSEMENT FOR CARD REPLACEMENT",
    "ENDORSEMENT FOR CARD PIN RESET",
    "ENDORSEMENT FOR CARD REACTIVATION",
    "ENDORSEMENT FOR CARD PIN MAILER",
    "OED MEMORANDUM LETTERS",
    "CERTIFICATIONS",
    "REQUEST LETTER WITHIN LTFRB",
    "LETTER OF NOTICE",
    "LETTER OF INVITATION AND MEETINGS",
    "FUEL SUBSIDY CERTIFICATES",
    "RESOLUTION NO. FOR CORP/COOP",
    "CHECKLIST",
    "MOA (GET CORP VS TRAFECO)",
    "THE GOOD BUS",
    "SUPER 5 TRANSPORT",
    "ACCIDENT REPORT",
    "SPECIAL OFFICE ORDER",
    "ISSUES/COMPLAINTS",
    "PERSONAL FILE",
    "TRANSPORT GROUP/S FILES",
    "BUREAU OF INTERNAL REVENUE",
    "ENDORSEMENT LETTER",
    "REQUISITION AND ISSUE SLIP",
    "ENDORSEMENT LETTER(RECEIVED COPY)",
    "FOR GOVERNOR UNABIA",
    "MOA FOR WORK IMMERSION PARTNERSHIP",
    "SCP - LGU",
    "LETTER FROM CITY OF CAGAYAN DE ORO",
    "COMISSION ON AUDIT",
    "RURAL TRANSIT MINDANAO INC",
    "MEMORANDUM LETTER",
    "ENDORSEMENT LETTER & MEMORANDUM ORDER"
);

$categoriesList = array(
    "Incoming Communication",
    "Outgoing Communication",
    "SPC",
    "Probation/Monitoring",
    "Authorities",
    "Inspections/Audits",
    "Hearing/Cases",
    "Training/Seminars"
);

// Determine document id
$id = 0;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} elseif (isset($_POST['doc_id'])) {
    $id = intval($_POST['doc_id']);
}

if (!$id) {
    echo "<p class='text-red-600 font-semibold'>Invalid document ID.</p>";
    include("includes/footer.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title'] ?? '');
    $status = mysqli_real_escape_string($conn, $_POST['status'] ?? '');
    $location = mysqli_real_escape_string($conn, $_POST['location'] ?? '');
    $category = mysqli_real_escape_string($conn, $_POST['category'] ?? '');
    $submitted_by = mysqli_real_escape_string($conn, $_POST['submitted_by'] ?? '');

    if (!empty($_POST['submitted_date'])) {
        $dt = str_replace('T', ' ', $_POST['submitted_date']);
        if (strlen($dt) === 16) $dt .= ':00';
        $submitted_date = "'" . mysqli_real_escape_string($conn, $dt) . "'";
    } else {
        $submitted_date = "NOW()";
    }

    $sql = "UPDATE documents 
            SET title='$title',
                status='$status',
                location='$location',
                category='$category',
                submitted_by='$submitted_by',
                submitted_date=$submitted_date,
                last_update=NOW()
            WHERE doc_id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='mt-6 bg-green-100 border-2 border-green-600 p-4 rounded'>
                <p class='text-green-800 font-bold text-center'>✓ DOCUMENT UPDATED SUCCESSFULLY</p>
              </div>";
    } else {
        echo "<div class='mt-6 bg-red-100 border-2 border-red-600 p-4 rounded'>
                <p class='text-red-800 font-bold text-center'>✗ ERROR: " . htmlspecialchars($conn->error) . "</p>
              </div>";
    }
}

// Fetch document
$sql = "SELECT * FROM documents WHERE doc_id = $id";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $doc = $result->fetch_assoc();
} else {
    echo "<p class='text-red-600 font-semibold'>Document not found.</p>";
    include("includes/footer.php");
    exit;
}

// prepare submitted_date
$submitted_value = '';
if (!empty($doc['submitted_date']) && $doc['submitted_date'] !== '0000-00-00 00:00:00') {
    $ts = strtotime($doc['submitted_date']);
    if ($ts !== false) {
        $submitted_value = date('Y-m-d\TH:i', $ts);
    }
}
?>

<div class="container mx-auto px-4 py-6 min-h-screen">
  <div class="bg-white border-2 border-gray-300 p-8 h-full">
    <form method="POST" action="" class="h-full">
      <input type="hidden" name="doc_id" value="<?php echo (int)$id; ?>">

      <div class="border-b-2 border-gray-200 pb-4 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">DOCUMENT INFORMATION</h3>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Left Column -->
        <div class="space-y-6">
          <!-- Title -->
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Document Title *</label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($doc['title'] ?? ''); ?>"
                   required class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
          </div>

          <!-- Status -->
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Status</label>
            <select name="status"
                    class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
              <option value="Pending" <?php if (($doc['status'] ?? '') === "Pending") echo "selected"; ?>>Pending</option>
              <option value="Approved" <?php if (($doc['status'] ?? '') === "Approved") echo "selected"; ?>>Approved</option>
              <option value="In Review" <?php if (($doc['status'] ?? '') === "In Review") echo "selected"; ?>>In Review</option>
              <option value="Rejected" <?php if (($doc['status'] ?? '') === "Rejected") echo "selected"; ?>>Rejected</option>
            </select>
          </div>

          <!-- Location Folder -->
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Location Folder *</label>
            <select name="location" id="locationSelect" required
                    class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
              <?php
              foreach ($locationsList as $loc) {
                  $sel = (($doc['location'] ?? '') === $loc) ? ' selected' : '';
                  echo '<option value="' . htmlspecialchars($loc, ENT_QUOTES) . '"' . $sel . '>' . htmlspecialchars($loc) . '</option>';
              }
              ?>
              <option value="OTHERS">OTHERS</option>
            </select>
          </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
          <!-- Category -->
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Category *</label>
            <select name="category" id="categorySelect" required
                    class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
              <?php
              foreach ($categoriesList as $cat) {
                  $sel = (($doc['category'] ?? '') === $cat) ? ' selected' : '';
                  echo '<option value="' . htmlspecialchars($cat, ENT_QUOTES) . '"' . $sel . '>' . htmlspecialchars($cat) . '</option>';
              }
              ?>
              <option value="OTHERS">OTHERS</option>
            </select>
          </div>

          <!-- Submitted By -->
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Submitted By *</label>
            <input type="text" name="submitted_by" value="<?php echo htmlspecialchars($doc['submitted_by'] ?? ''); ?>"
                   required class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
          </div>

          <!-- Submitted Date -->
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">Submitted Date *</label>
            <input type="datetime-local" name="submitted_date" value="<?php echo htmlspecialchars($submitted_value); ?>"
                   required class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
          </div>
        </div>
      </div>

      <!-- Submit -->
      <div class="border-t-2 border-gray-200 pt-6 mt-8">
        <div class="flex justify-center gap-4">
          <button type="submit"
                  class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 px-8 border-2 border-blue-900 uppercase tracking-wide transition-colors">
            UPDATE DOCUMENT
          </button>
          <a href="index.php"
             class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-8 border-2 border-gray-500 uppercase tracking-wide transition-colors">
            CANCEL
          </a>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  function handleOthers(selectElement, message) {
    selectElement.addEventListener('change', function () {
      if (this.value === 'OTHERS') {
        let newValue = prompt(message);
        if (newValue && newValue.trim() !== "") {
          let option = new Option(newValue, newValue, true, true);
          this.add(option);
        } else {
          this.value = ""; // reset if cancel or empty
        }
      }
    });
  }

  handleOthers(document.getElementById('locationSelect'), "Please enter a new Location Folder:");
  handleOthers(document.getElementById('categorySelect'), "Please enter a new Category:");
});
</script>

<?php include("includes/footer.php"); ?>
