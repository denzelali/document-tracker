<?php
// pagination.php
// Default values
$limit = 10; // number of records per page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$page = $page < 1 ? 1 : $page; // prevent negative pages
$offset = ($page - 1) * $limit;

// Ensure limit and offset are integers
$limit = (int)$limit;
$offset = (int)$offset;

// Count total records
$total_sql = "SELECT COUNT(*) AS total FROM documents";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

// Use prepared statement - show 10 records per page
$sql = "SELECT * FROM documents ORDER BY doc_id DESC LIMIT ? OFFSET ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<!-- Government Standard Pagination -->
<div style="margin: 20px 0; padding: 15px; border: 1px solid #d1d5db; background: #f9fafb;">
    
    <!-- Results Summary -->
    <div style="text-align: center; margin-bottom: 15px; padding: 8px; background: white;">
        <strong style="color: #1f2937; font-size: 14px;">
            Page <?php echo $page; ?> of <?php echo $total_pages; ?> | 
            <?php echo number_format($total_records); ?> Total Records
        </strong>
    </div>
    
    <!-- Navigation Controls -->
    <div style="text-align: center;">
        
        <!-- Previous -->
        <?php if ($page > 1): ?>
            <a href="?page=<?php echo $page-1; ?>" 
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
            <a href="?page=1" style="display: inline-block; padding: 8px 12px; margin: 2px; background: white; color: #1f2937; text-decoration: none; border: 1px solid #d1d5db;">1</a>
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
                <a href="?page=<?php echo $i; ?>" 
                   style="display: inline-block; padding: 8px 12px; margin: 2px; background: white; color: #1f2937; text-decoration: none; border: 1px solid #d1d5db;">
                    <?php echo $i; ?>
                </a>
            <?php endif; ?>
        <?php endfor; ?>
        
        <?php if ($end < $total_pages): ?>
            <?php if ($end < $total_pages - 1): ?>
                <span style="display: inline-block; padding: 8px 12px; margin: 2px; color: #9ca3af;">...</span>
            <?php endif; ?>
            <a href="?page=<?php echo $total_pages; ?>" style="display: inline-block; padding: 8px 12px; margin: 2px; background: white; color: #1f2937; text-decoration: none; border: 1px solid #d1d5db;"><?php echo $total_pages; ?></a>
        <?php endif; ?>
        
        <!-- Next -->
        <?php if ($page < $total_pages): ?>
            <a href="?page=<?php echo $page+1; ?>" 
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