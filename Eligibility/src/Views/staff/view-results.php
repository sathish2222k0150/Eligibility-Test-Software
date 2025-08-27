<?php include __DIR__ . '/../layouts/staff-header.php'; ?>
<section class="content-header">
  <div class="container-fluid"><h1>Student Test Results</h1></div>
</section>
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <table class="table table-bordered">
          <thead><tr><th>Student</th><th>Test</th><th>Submitted On</th><th>Status</th><th>Score</th><th>Action</th></tr></thead>
          <tbody>
            <?php foreach($attempts as $attempt): ?>
            <tr>
              <td><?php echo htmlspecialchars($attempt['student_name']); ?></td>
              <td><?php echo htmlspecialchars($attempt['test_title']); ?></td>
              <td><?php echo date('d M Y, H:i', strtotime($attempt['end_time'])); ?></td>
              <td>
                <?php if($attempt['status'] == 'completed'): ?>
                  <span class="badge badge-warning">Pending Grade</span>
                <?php else: ?>
                  <span class="badge badge-success">Graded</span>
                <?php endif; ?>
              </td>
              <td><?php echo $attempt['score'] ?? 'N/A'; ?></td>
              <td>
                <?php if($attempt['status'] == 'completed'): ?>
                  <a href="index.php?action=grade-test&id=<?php echo $attempt['id']; ?>" class="btn btn-primary btn-sm">Grade</a>
                <?php else: ?>
                  <a href="index.php?action=grade-test&id=<?php echo $attempt['id']; ?>" class="btn btn-secondary btn-sm">View</a>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
<?php include __DIR__ . '/../layouts/staff-footer.php'; ?>