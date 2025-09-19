<?php include("config/db.php"); ?>
<?php include("includes/header.php"); ?>

<?php
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // sanitize input
    $sql = "SELECT * FROM documents WHERE doc_id = $id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $doc = $result->fetch_assoc();
    } else {
        echo "<p class='text-red-600 font-semibold'>Document not found.</p>";
        exit;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    $location = mysqli_real_escape_string($conn, $_POST['location']);
    $category = mysqli_real_escape_string($conn, $_POST['category']);
    $submitted_by = mysqli_real_escape_string($conn, $_POST['submitted_by']);
    $submitted_date = !empty($_POST['submitted_date']) 
                        ? "'" . mysqli_real_escape_string($conn, $_POST['submitted_date']) . "'" 
                        : "NOW()";

    $sql = "UPDATE documents 
            SET title='$title', status='$status', location='$location', category='$category', submitted_by='$submitted_by', submitted_date=$submitted_date, last_update=NOW()
            WHERE doc_id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "<div class='mt-6 bg-green-100 border-2 border-green-600 p-4 rounded'>
                <p class='text-green-800 font-bold text-center'>✓ DOCUMENT UPDATED SUCCESSFULLY</p>
              </div>";
    } else {
        echo "<div class='mt-6 bg-red-100 border-2 border-red-600 p-4 rounded'>
                <p class='text-red-800 font-bold text-center'>✗ ERROR: " . $conn->error . "</p>
              </div>";
    }
}
?>

<div class="container mx-auto px-4 py-6 min-h-screen">
  <!-- Official Header -->
  <!-- <div class="bg-blue-900 text-white p-6 mb-6">
    <h2 class="text-2xl font-bold text-center">DOCUMENT MANAGEMENT SYSTEM</h2>
    <p class="text-center text-blue-100 mt-2">Edit Document</p>
  </div> -->

  <!-- Form Section - Full Width -->
  <div class="bg-white border-2 border-gray-300 p-8 h-full">
    <form method="POST" action="" class="h-full">
      
      <!-- Document Information Section -->
      <div class="border-b-2 border-gray-200 pb-4 mb-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">DOCUMENT INFORMATION</h3>
      </div>

      <!-- Two Column Grid Layout -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        
        <!-- Left Column -->
        <div class="space-y-6">
          
          <!-- Title Field -->
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">
              Document Title *
            </label>
            <input type="text" name="title" value="<?php echo htmlspecialchars($doc['title']); ?>" 
                   required class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50" required>
          </div>

          <!-- Status -->
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">
              Status
            </label>
            <select name="status" 
                    class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
              <option value="Pending" <?php if($doc['status']=="Pending") echo "selected"; ?>>Pending</option>
              <option value="Approved" <?php if($doc['status']=="Approved") echo "selected"; ?>>Approved</option>
              <option value="In Review" <?php if($doc['status']=="In Review") echo "selected"; ?>>In Review</option>
              <option value="Rejected" <?php if($doc['status']=="Rejected") echo "selected"; ?>>Rejected</option>
            </select>
          </div>

          <!-- Location Folder -->
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">
              Location Folder *
            </label>
            <select name="location" 
                   required class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
            <option value="ENDORSEMENT FOR FUEL CARD RELEASE" <?php if($doc['location']=="ENDORSEMENT FOR FUEL CARD RELEASE") echo "selected"; ?>>ENDORSEMENT FOR FUEL CARD RELEASE</option>
            <option value="ENDORSEMENT FOR CARD REPLACEMENT" <?php if($doc['location']=="ENDORSEMENT FOR CARD REPLACEMENT") echo "selected"; ?>>ENDORSEMENT FOR CARD REPLACEMENT</option>
            <option value="ENDORSEMENT FOR CARD PIN RESET" <?php if($doc['location']=="ENDORSEMENT FOR CARD PIN RESET") echo "selected"; ?>>ENDORSEMENT FOR CARD PIN RESET</option>
            <option value="ENDORSEMENT FOR CARD REACTIVATION" <?php if($doc['location']=="ENDORSEMENT FOR CARD REACTIVATION") echo "selected"; ?>>ENDORSEMENT FOR CARD REACTIVATION</option>
            <option value="ENDORSEMENT FOR CARD PIN MAILER" <?php if($doc['location']=="ENDORSEMENT FOR CARD PIN MAILER") echo "selected"; ?>>ENDORSEMENT FOR CARD PIN MAILER</option>
            <option value="OED MEMORANDUM LETTERS" <?php if($doc['location']=="OED MEMORANDUM LETTERS") echo "selected"; ?>>OED MEMORANDUM LETTERS</option>
            <option value="CERTIFICATIONS" <?php if($doc['location']=="CERTIFICATIONS") echo "selected"; ?>>CERTIFICATIONS</option>
            <option value="REQUEST LETTER WITHIN LTFRB" <?php if($doc['location']=="REQUEST LETTER WITHIN LTFRB") echo "selected"; ?>>REQUEST LETTER WITHIN LTFRB</option>
            <option value="LETTER OF NOTICE" <?php if($doc['location']=="LETTER OF NOTICE") echo "selected"; ?>>LETTER OF NOTICE</option>
            <option value="LETTER OF INVITATION AND MEETINGS" <?php if($doc['location']=="LETTER OF INVITATION AND MEETINGS") echo "selected"; ?>>LETTER OF INVITATION AND MEETINGS</option>
            <option value="FUEL SUBSIDY CERTIFICATES" <?php if($doc['location']=="FUEL SUBSIDY CERTIFICATES") echo "selected"; ?>>FUEL SUBSIDY CERTIFICATES</option>
            <option value="RESOLUTION NO. FOR CORP/COOP" <?php if($doc['location']=="RESOLUTION NO. FOR CORP/COOP") echo "selected"; ?>>RESOLUTION NO. FOR CORP/COOP</option>
            <option value="CHECKLIST" <?php if($doc['location']=="CHECKLIST") echo "selected"; ?>>CHECKLIST</option>
            <option value="MOA (GET CORP VS TRAFECO)" <?php if($doc['location']=="MOA (GET CORP VS TRAFECO)") echo "selected"; ?>>MOA (GET CORP VS TRAFECO)</option>
            <option value="THE GOOD BUS" <?php if($doc['location']=="THE GOOD BUS") echo "selected"; ?>>THE GOOD BUS</option>
            <option value="SUPER 5 TRANSPORT" <?php if($doc['location']=="SUPER 5 TRANSPORT") echo "selected"; ?>>SUPER 5 TRANSPORT</option>
            <option value="ACCIDENT REPORT" <?php if($doc['location']=="ACCIDENT REPORT") echo "selected"; ?>>ACCIDENT REPORT</option>
            <option value="SPECIAL OFFICE ORDER" <?php if($doc['location']=="SPECIAL OFFICE ORDER") echo "selected"; ?>>SPECIAL OFFICE ORDER</option>
            <option value="ISSUES/COMPLAINTS" <?php if($doc['location']=="ISSUES/COMPLAINTS") echo "selected"; ?>>ISSUES/COMPLAINTS</option>
            <option value="PERSONAL FILE" <?php if($doc['location']=="PERSONAL FILE") echo "selected"; ?>>PERSONAL FILE</option>
            <option value="TRANSPORT GROUP/S FILES" <?php if($doc['location']=="TRANSPORT GROUP/S FILES") echo "selected"; ?>>TRANSPORT GROUP/S FILES</option>
            <option value="BUREAU OF INTERNAL REVENUE" <?php if($doc['location']=="BUREAU OF INTERNAL REVENUE") echo "selected"; ?>>BUREAU OF INTERNAL REVENUE</option>
            <option value="ENDORSEMENT LETTER" <?php if($doc['location']=="ENDORSEMENT LETTER") echo "selected"; ?>>ENDORSEMENT LETTER</option>
            <option value="REQUISITION AND ISSUE SLIP" <?php if($doc['location']=="REQUISITION AND ISSUE SLIP") echo "selected"; ?>>REQUISITION AND ISSUE SLIP</option>
            <option value="ENDORSEMENT LETTER(RECEIVED COPY)" <?php if($doc['location']=="ENDORSEMENT LETTER(RECEIVED COPY)") echo "selected"; ?>>ENDORSEMENT LETTER(RECEIVED COPY)</option>
            <option value="FOR GOVERNOR UNABIA" <?php if($doc['location']=="FOR GOVERNOR UNABIA") echo "selected"; ?>>FOR GOVERNOR UNABIA</option>
            <option value="MOA FOR WORK IMMERSION PARTNERSHIP" <?php if($doc['location']=="MOA FOR WORK IMMERSION PARTNERSHIP") echo "selected"; ?>>MOA FOR WORK IMMERSION PARTNERSHIP</option>
            <option value="SCP - LGU" <?php if($doc['location']=="SCP - LGU") echo "selected"; ?>>SCP - LGU</option>
            <option value="LETTER FROM CITY OF CAGAYAN DE ORO" <?php if($doc['location']=="LETTER FROM CITY OF CAGAYAN DE ORO") echo "selected"; ?>>LETTER FROM CITY OF CAGAYAN DE ORO</option>
            <option value="COMISSION ON AUDIT" <?php if($doc['location']=="COMISSION ON AUDIT") echo "selected"; ?>>COMISSION ON AUDIT</option>
            <option value="RURAL TRANSIT MINDANAO INC" <?php if($doc['location']=="RURAL TRANSIT MINDANAO INC") echo "selected"; ?>>RURAL TRANSIT MINDANAO INC</option>
            <option value="MEMORANDUM LETTER" <?php if($doc['location']=="MEMORANDUM LETTER") echo "selected"; ?>>MEMORANDUM LETTER</option>
            <option value="ENDORSEMENT LETTER & MEMORANDUM ORDER" <?php if($doc['location']=="ENDORSEMENT LETTER & MEMORANDUM ORDER") echo "selected"; ?>>ENDORSEMENT LETTER & MEMORANDUM ORDER</option>
            </select>
          </div>

          

        </div>

        <!-- Right Column -->
        <div class="space-y-6">
          
          <!-- Category -->
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">
              Category *
            </label>
            <select name="category" 
                   required class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
                   <option value="">-- Select Category --</option>
            <option value="Incoming Communication" <?php if($doc['category']=="Incoming Communication") echo "selected"; ?>>Incoming Communication</option>
            <option value="Outgoing Communication" <?php if($doc['category']=="Outgoing Communication") echo "selected"; ?>>Outgoing Communication</option>
            <option value="SPC" <?php if($doc['category']=="SPC") echo "selected"; ?>>SPC</option>
            <option value="Probation/Monitoring" <?php if($doc['category']=="Probation/Monitoring") echo "selected"; ?>>Probation/Monitoring</option>
            <option value="Authorities" <?php if($doc['category']=="Authorities") echo "selected"; ?>>Authorities</option>
            <option value="Inspections/Audits" <?php if($doc['category']=="Inspections/Audits") echo "selected"; ?>>Inspections/Audits</option>
            <option value="Hearing/Cases" <?php if($doc['category']=="Hearing/Cases") echo "selected"; ?>>Hearing/Cases</option>
            <option value="Training/Seminars" <?php if($doc['category']=="Training/Seminars") echo "selected"; ?>>Training/Seminars</option>
            </select>
          </div>

          <!-- Submitted By -->
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">
              Submitted By *
            </label>
            <input type="text" name="submitted_by" value="<?php echo htmlspecialchars($doc['submitted_by']); ?>" 
                   required class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
          </div>
          
          <!-- Submitted Date -->
          <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">
              Submitted Date *
            </label>
            <input type="datetime-local" name="submitted_date" value="<?php echo date('Y-m-d\TH:i', strtotime($doc['submitted_date'])); ?>" 
                   required class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50">
          </div>


          <!-- Additional Description/Notes Field -->
          <!-- <div>
            <label class="block text-sm font-bold text-gray-700 mb-2 uppercase">
              Notes
            </label>
            <textarea name="description" rows="4"
                     class="w-full p-3 border-2 border-gray-400 focus:border-blue-600 focus:outline-none bg-gray-50 resize-vertical"
                     placeholder="Additional information about the document..."></textarea>
          </div> -->

        </div>
      </div>

      <!-- Submit Section -->
      <div class="border-t-2 border-gray-200 pt-6 mt-8">
        <div class="flex justify-center gap-4">
          <button type="submit" 
                  class="bg-blue-900 hover:bg-blue-800 text-white font-bold py-3 px-8 border-2 border-blue-900 uppercase tracking-wide transition-colors">
            UPDATE DOCUMENT
          </button>
          <a href="index.php" 
             class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-8 border-2 border-gray-500 uppercase tracking-wide transition-colors text-decoration-none">
            CANCEL
          </a>
        </div>
      </div>
    </form>
  </div>

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
  min-height: 100vh;
}

/* Full height container */
/* .container {
  min-height: calc(100vh - 2rem);
} */

/* Responsive adjustments */
@media (max-width: 1023px) {
  .grid-cols-1.lg\:grid-cols-2 {
    grid-template-columns: 1fr;
  }
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

/* Smooth transitions */
button, a {
  transition: all 0.2s ease-in-out;
}

/* Form validation styling */
input:invalid,
select:invalid {
  border-color: #ef4444;
}

input:valid,
select:valid {
  border-color: #10b981;
}

/* Remove text decoration from links */
a {
  text-decoration: none;
}

a:hover {
  text-decoration: none;
}
</style>

<?php include("includes/footer.php"); ?>