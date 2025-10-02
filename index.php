<?php 
include("config/db.php"); 
include("config/database.sqlite");
include("includes/header.php"); 

// Pagination logic
$limit = 10; // number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = $page < 1 ? 1 : $page; // prevent negative pages
$offset = ($page - 1) * $limit;

// Ensure limit and offset are integers
$limit = (int)$limit;
$offset = (int)$offset;

// Handle search + filters
$search_condition = "";
$search_params = [];
$param_types = "";

// Search filter
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $keyword = $_GET['search'];
    $search_condition = "WHERE title LIKE ? OR category LIKE ? OR location LIKE ? OR submitted_by LIKE ?";
    $search_params = ["%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%"];
    $param_types = "ssss";
}

// Date filter (exact day)
if (isset($_GET['filter_date']) && !empty($_GET['filter_date'])) {
    $date = $_GET['filter_date'];
    $search_condition .= (empty($search_condition) ? "WHERE " : " AND ") . "DATE(submitted_date) = ?";
    $search_params[] = $date;
    $param_types .= "s";
}

// Month filter (YYYY-MM)
if (isset($_GET['filter_month']) && !empty($_GET['filter_month'])) {
    $month = $_GET['filter_month'];
    $search_condition .= (empty($search_condition) ? "WHERE " : " AND ") . "DATE_FORMAT(submitted_date, '%Y-%m') = ?";
    $search_params[] = $month;
    $param_types .= "s";
}

// Count total records (with filters/search)
$total_sql = "SELECT COUNT(*) AS total FROM documents $search_condition";
if (!empty($search_params)) {
    $total_stmt = $conn->prepare($total_sql);
    $total_stmt->bind_param($param_types, ...$search_params);
    $total_stmt->execute();
    $total_result = $total_stmt->get_result();
} else {
    $total_result = $conn->query($total_sql);
}
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);
?>

<div class="container mx-auto px-2 sm:px-4 py-6 min-h-screen">


  <!-- Date / Month Filter -->
  <!-- Separate Date + Month Filters with Apply Button -->
<div class="mb-3 flex flex-col sm:flex-row sm:space-x-4 space-y-2 sm:space-y-0 font-sans text-sm">


  <!-- Date Filter -->
  <div class="flex flex-col items-start">
    <label class="text-xs font-semibold text-gray-700" style="font-family: Arial, Helvetica, sans-serif;">Filter by Date</label>
    <form method="GET" class="flex items-center space-x-1">
      <input type="date" name="filter_date"
             value="<?php echo isset($_GET['filter_date']) ? $_GET['filter_date'] : ''; ?>"
             class="border border-gray-300 rounded px-1 py-1 text-xs focus:ring-1 focus:ring-blue-500 focus:border-blue-500" />
      <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs font-semibold" title="Apply Date Filter">
        Apply
      </button>
    </form>
  </div>

  <!-- Month Filter -->
  <div class="flex flex-col items-start">
    <label class="text-xs font-semibold text-gray-700" style="font-family: Arial, Helvetica, sans-serif;">Filter by Month</label>
    <form method="GET" class="flex items-center space-x-1">
      <input type="month" name="filter_month"
             value="<?php echo isset($_GET['filter_month']) ? $_GET['filter_month'] : ''; ?>"
             class="border border-gray-300 rounded px-1 py-1 text-xs focus:ring-1 focus:ring-green-500 focus:border-green-500" />
      <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-2 py-1 rounded text-xs font-semibold" title="Apply Month Filter">
        Apply
      </button>
    </form>
  </div>
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
<thead class="bg-blue-900 text-white uppercase text-[10px] sm:text-xs">
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
          // Fetch documents with pagination and filters
          $sql = "SELECT * FROM documents 
        WHERE status != 'Deleted' 
        " . (!empty($search_condition) ? " AND " . substr($search_condition, 6) : "") . "
        ORDER BY doc_id ASC 
        LIMIT ? OFFSET ?";
          
          if (!empty($search_params)) {
              // With filters/search
              $stmt = $conn->prepare($sql);
              $param_types_with_limit = $param_types . "ii";
              $params_with_limit = array_merge($search_params, [$limit, $offset]);
              $stmt->bind_param($param_types_with_limit, ...$params_with_limit);
              $stmt->execute();
              $result = $stmt->get_result();
          } else {
              // Without filters
              $stmt = $conn->prepare($sql);
              $stmt->bind_param("ii", $limit, $offset);
              $stmt->execute();
              $result = $stmt->get_result();
          }

          if ($result->num_rows > 0) {
              $row_count = 0;

            while ($row = $result->fetch_assoc()) {
    $row_count++;
    $row_class = ($row_count % 2 == 0) ? "bg-gray-50" : "bg-white";

    echo "<tr class='{$row_class} border-b border-gray-200 hover:bg-blue-50 transition-colors'>
            <td class='px-2 py-2 sm:px-4 sm:py-3 font-medium'>
{$row_count}</td>
            <td class='px-2 py-2 sm:px-4 sm:py-3 font-medium
 text-blue-900'>" . htmlspecialchars($row['title']) . "</td>
            <td class='px-2 py-2 sm:px-4 sm:py-3 font-medium'>
" . htmlspecialchars($row['location']) . "</td>
            <td class='px-2 py-2 sm:px-4 sm:py-3 font-medium'>
" . htmlspecialchars($row['category']) . "</td>
            <td class='px-2 py-2 sm:px-4 sm:py-3 font-medium'>
" . htmlspecialchars($row['submitted_by']) . "</td>
            <td class='px-2 py-2 sm:px-4 sm:py-3 font-medium'>
" . date('m/d/Y h:i A', strtotime($row['submitted_date'])) . "</td>
            <td class='px-2 py-2 sm:px-4 sm:py-3 font-medium'>

              <span class='px-3 py-1 rounded-full text-white text-xs font-bold uppercase " .
                ($row['status'] == 'Pending' ? 'bg-gray-600' :
                ($row['status'] == 'Approved' ? 'bg-green-600' :
                ($row['status'] == 'In Review' ? 'bg-yellow-600' :
                ($row['status'] == 'Rejected' ? 'bg-red-600' : 'bg-gray-600')))) . "'>
                {$row['status']}
              </span>
            </td>
            <td class='px-2 py-2 sm:px-4 sm:py-3 font-medium'>
" . date('m/d/Y h:i A', strtotime($row['last_update'])) . "</td>
            <td class='px-2 py-2 sm:px-4 sm:py-3 font-medium'>

              <div class='flex space-x-2'>

                <!-- Edit Form -->
                <form action='edit_document.php' method='get' style='display:inline;'>
                  <input type='hidden' name='id' value='" . $row['doc_id'] . "'>
                  <button 
                      type='submit' 
                      class='bg-blue-600 hover:bg-blue-700 text-white p-2 rounded transition-colors' 
                      title='Edit Document'>
                    <svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='2'>
                      <path stroke-linecap='round' stroke-linejoin='round' d='M11 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5m-7-7l7 7-4 4-7-7 4-4z' />
                    </svg>
                  </button>
                </form>

                <!-- Delete Form -->
                <form action='delete_document.php' method='get' style='display:inline;' 
                      onsubmit=\"return confirm('Are you sure you want to delete this document?');\">
                  <input type='hidden' name='id' value='" . $row['doc_id'] . "'>
                  <button 
                      type='submit' 
                      class='bg-red-600 hover:bg-red-700 text-white p-2 rounded transition-colors' 
                      title='Delete Document'>
                    <svg xmlns='http://www.w3.org/2000/svg' class='h-4 w-4' fill='none' viewBox='0 0 24 24' stroke='currentColor' stroke-width='2'>
                      <path stroke-linecap='round' stroke-linejoin='round' d='M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0h3m-4 0V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2' />
                    </svg>
                  </button>
                </form>

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
                        <p class='text-sm text-gray-500 mt-1'>Try adjusting your filters.</p>
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
        echo "<span class='font-bold'>Total Documents: {$total_records}</span>";
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

  <!-- Pagination -->
  <div style="margin: 20px 0; padding: 15px; border: 1px solid #d1d5db; background: #f9fafb;">
    
    <!-- Results Summary -->
    <div style="text-align: center; margin-bottom: 15px; padding: 8px; background: white;">
        <strong style="color: #1f2937; font-size: 14px;">
            Page <?php echo $page; ?> of <?php echo $total_pages; ?> | 
            <?php echo number_format($total_records); ?> Total Records
        </strong>
    </div>
    
    <!-- Navigation Controls -->
    <div class="flex flex-wrap justify-center gap-2 text-sm">

        
        <!-- Previous -->
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page-1; ?><?php echo isset($_SERVER['QUERY_STRING']) ? '&'.http_build_query(array_merge($_GET, ["page" => $page-1])) : ''; ?>" 
               style="display: inline-block; padding: 8px 16px; margin: 2px; background: white; color: #1f2937; text-decoration: none; border: 1px solid #d1d5db; font-weight: 600;">
                ← Previous
            </a>
        <?php else: ?>
            <span style="display: inline-block; padding: 8px 16px; margin: 2px; background: #f3f4f6; color: #9ca3af; border: 1px solid #d1d5db; font-weight: 600;">
                ← Previous
            </span>
        <?php endif; ?>
        
        <!-- Page Numbers -->
        <?php 
        $start = max(1, $page - 3);
        $end = min($total_pages, $page + 3);
        
        if ($start > 1): ?>
            <a href="?page=1<?php echo isset($_SERVER['QUERY_STRING']) ? '&'.http_build_query(array_merge($_GET, ["page" => 1])) : ''; ?>" style="display: inline-block; padding: 8px 12px; margin: 2px; background: white; color: #1f2937; text-decoration: none; border: 1px solid #d1d5db;">1</a>
            <?php if ($start > 2): ?>
                <span style="display: inline-block; padding: 8px 12px; margin: 2px; color: #9ca3af;">...</span>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php for ($i = $start; $i <= $end; $i++): ?>
            <?php if ($i == $page): ?>
                <span style="display: inline-block; padding: 8px 12px; margin: 2px; background: #1f2937; color: white; border: 1px solid #1f2937; font-weight: bold;">
                    <?php echo $i; ?>
                </span>
            <?php else: ?>
                <a href="?page=<?php echo $i; ?><?php echo isset($_SERVER['QUERY_STRING']) ? '&'.http_build_query(array_merge($_GET, ["page" => $i])) : ''; ?>" 
                   style="display: inline-block; padding: 8px 12px; margin: 2px; background: white; color: #1f2937; text-decoration: none; border: 1px solid #d1d5db;">
                    <?php echo $i; ?>
                </a>
            <?php endif; ?>
        <?php endfor; ?>
        
        <?php if ($end < $total_pages): ?>
            <?php if ($end < $total_pages - 1): ?>
                <span style="display: inline-block; padding: 8px 12px; margin: 2px; color: #9ca3af;">...</span>
            <?php endif; ?>
            <a href="?page=<?php echo $total_pages; ?><?php echo isset($_SERVER['QUERY_STRING']) ? '&'.http_build_query(array_merge($_GET, ["page" => $total_pages])) : ''; ?>" style="display: inline-block; padding: 8px 12px; margin: 2px; background: white; color: #1f2937; text-decoration: none; border: 1px solid #d1d5db;"><?php echo $total_pages; ?></a>
        <?php endif; ?>
        
        <!-- Next -->
        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page+1; ?><?php echo isset($_SERVER['QUERY_STRING']) ? '&'.http_build_query(array_merge($_GET, ["page" => $page+1])) : ''; ?>" 
               style="display: inline-block; padding: 8px 16px; margin: 2px; background: white; color: #1f2937; text-decoration: none; border: 1px solid #d1d5db; font-weight: 600;">
                Next → 
            </a>
        <?php else: ?>
            <span style="display: inline-block; padding: 8px 16px; margin: 2px; background: #f3f4f6; color: #9ca3af; border: 1px solid #d1d5db; font-weight: 600;">
                Next → 
            </span>
        <?php endif; ?>
        
    </div>
  </div>

</div>

<?php include("includes/footer.php"); ?>
