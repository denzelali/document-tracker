<?php 
include("config/db.php"); 
include("includes/header.php"); 
?>

<div class="container mx-auto px-4 py-6 min-h-screen">

  <!-- Action Bar: Search + Add Document -->
  <div class="flex justify-between items-center mb-6">
    <!-- <form action="index.php" method="GET" class="flex items-center space-x-2">
      <input 
        type="text" 
        name="search" 
        placeholder="Search documents..." 
        class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-gray-700"
        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
      >
      <button type="submit" 
              class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
        Search
      </button>
    </form> -->

    <!-- <a href="add_document.php" 
       class="bg-blue-600 text-white px-6 py-3 rounded font-medium hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center space-x-2">
      <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
      </svg>
      <span>New Document</span>
    </a> -->
  </div>

  <!-- Documents Table -->
  <div class="bg-white border-2 border-gray-300 overflow-hidden -mt-2">

    <!-- Table Header -->
    <div class="bg-gray-100 border-b-2 border-gray-300 p-4">
      <h4 class="font-bold text-2xl text-gray-800 uppercase text-center">Document List</h4>
    </div>

    <!-- Table Content -->
    <div class="overflow-x-auto">
      <table class="min-w-full text-sm text-left text-gray-700">
        <thead class="bg-blue-900 text-white uppercase text-xs">
          <tr>
            <th class="px-4 py-3 font-bold">ID</th>
            <th class="px-4 py-3 font-bold">DOCUMENT TITLE</th>
            <th class="px-4 py-3 font-bold">LOCATION</th>
            <th class="px-4 py-3 font-bold">CATEGORY</th>
            <th class="px-4 py-3 font-bold">SUBMITTED BY</th>
            <th class="px-4 py-3 font-bold">SUBMITTED DATE</th>
            <th class="px-4 py-3 font-bold">STATUS</th>
            <th class="px-4 py-3 font-bold">LAST UPDATE</th>
            <th class="px-4 py-3 font-bold">ACTIONS</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Fetch documents with search functionality
          $sql = "SELECT * FROM documents ORDER BY last_update DESC";
          if (isset($_GET['search']) && !empty($_GET['search'])) {
              $keyword = $conn->real_escape_string($_GET['search']);
              $sql = "SELECT * FROM documents 
                      WHERE title LIKE '%$keyword%' OR category LIKE '%$keyword%' 
                      ORDER BY last_update DESC";
          }
          $result = $conn->query($sql);

          if ($result->num_rows > 0) {
              $row_count = 0;
              while ($row = $result->fetch_assoc()) {
                  $row_count++;
                  $row_class = ($row_count % 2 == 0) ? "bg-gray-50" : "bg-white";
                  echo "<tr class='{$row_class} border-b border-gray-200 hover:bg-blue-50 transition-colors'>
                          <td class='px-4 py-3 font-medium'>{$row['doc_id']}</td>
                          <td class='px-4 py-3 font-medium text-blue-900'>" . htmlspecialchars($row['title']) . "</td>
                          <td class='px-4 py-3'>" . htmlspecialchars($row['location']) . "</td>
                          <td class='px-4 py-3'>" . htmlspecialchars($row['category']) . "</td>
                          <td class='px-4 py-3'>" . htmlspecialchars($row['submitted_by']) . "</td>
                          <td class='px-4 py-3'>" . date('m/d/Y h:i A', strtotime($row['submitted_date'])) . "</td>
                          <td class='px-4 py-3'>
                            <span class='px-3 py-1 rounded-full text-white text-xs font-bold uppercase " .
                              ($row['status'] == 'Pending' ? 'bg-gray-600' :
                               ($row['status'] == 'Approved' ? 'bg-green-600' :
                               ($row['status'] == 'In Review' ? 'bg-yellow-600' :
                               ($row['status'] == 'Rejected' ? 'bg-red-600' : 'bg-gray-600')))) . "'>
                              {$row['status']}
                            </span>
                          </td>
                          <td class='px-4 py-3'>" . date('m/d/Y h:i A', strtotime($row['last_update'])) . "</td>
                          <td class='px-4 py-3'>
                            <div class='flex space-x-2'>
                              <a class='bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs font-bold uppercase transition-colors' 
                                 href='edit_document.php?id={$row['doc_id']}' 
                                 title='Edit Document'>EDIT</a>
                              <a class='bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs font-bold uppercase transition-colors' 
                                 href='delete_document.php?id={$row['doc_id']}' 
                                 title='Delete Document'
                                 onclick='return confirm(\"Are you sure you want to delete this document?\");'>DELETE</a>
                            </div>
                          </td>
                        </tr>";
              }
          } else {
              echo "<tr><td colspan='9' class='px-4 py-8 text-center text-gray-500 bg-gray-50'>
                      <div class='flex flex-col items-center'>
                        <svg class='w-12 h-12 text-gray-400 mb-4' fill='none' stroke='currentColor' viewBox='0 0 24 24'>
                          <path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'></path>
                        </svg>
                        <p class='text-lg font-semibold text-gray-600'>No documents found.</p>
                        <p class='text-sm text-gray-500 mt-1'>Start by adding your first document.</p>
                      </div>
                    </td></tr>";
          }
          ?>
        </tbody>
      </table>
    </div>

    <!-- Table Footer -->
    <div class="bg-gray-100 border-t-2 border-gray-300 p-4 flex justify-between items-center text-sm text-gray-600">
      <div>
        <?php 
        $count_sql = "SELECT COUNT(*) as total FROM documents";
        $count_result = $conn->query($count_sql);
        $total_docs = $count_result->fetch_assoc()['total'];
        echo "<span class='font-bold'>Total Documents: {$total_docs}</span>";
        ?>
      </div>
      <div class="flex space-x-4 text-xs">
        <?php 
        $status_sql = "SELECT status, COUNT(*) as count FROM documents GROUP BY status";
        $status_result = $conn->query($status_sql);
        while ($status_row = $status_result->fetch_assoc()) {
            $status = $status_row['status'];
            $count = $status_row['count'];
            $color = ($status == 'Pending' ? 'bg-gray-200 text-gray-700' :
                     ($status == 'Approved' ? 'bg-green-200 text-green-700' :
                     ($status == 'In Review' ? 'bg-yellow-200 text-yellow-700' :
                     ($status == 'Rejected' ? 'bg-red-200 text-red-700' : 'bg-gray-200 text-gray-700'))));
            echo "<span class='px-2 py-1 rounded {$color}'>{$status}: {$count}</span>";
        }
        ?>
      </div>
    </div>

  </div>

</div>

<?php include("includes/footer.php"); ?>
