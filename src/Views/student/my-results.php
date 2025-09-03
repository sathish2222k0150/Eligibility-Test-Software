<?php include __DIR__ . '/../layouts/student-header.php'; ?>
<section class="content-header">
  <div class="container-fluid"><h1>My Results</h1></div>
</section>
<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <table class="table table-bordered">
          <thead><tr><th>Test</th><th>Course</th><th>Date Completed</th><th>Score</th><th>Status</th><th>Action</th></tr></thead>
          <tbody>
            <?php foreach($attempts as $attempt): ?>
            <tr>
              <td><?php echo htmlspecialchars($attempt['test_title']); ?></td>
              <td><?php echo htmlspecialchars($attempt['course_name']); ?></td>
              <td><?php echo date('d M Y, H:i', strtotime($attempt['end_time'])); ?></td>
              <td>
                <?php if ($attempt['status'] == 'graded'): ?>
                  <strong><?php echo $attempt['score']; ?> / <?php echo $attempt['max_score']; ?></strong>
                <?php else: ?>
                  N/A
                <?php endif; ?>
              </td>
              <td>
                <?php if($attempt['status'] == 'completed'): ?>
                  <span class="badge badge-warning">Pending Evaluation</span>
                <?php else: ?>
                  <span class="badge badge-success">Graded</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if($attempt['status'] == 'graded'): ?>
                  <a href="index.php?action=view-attempt-details&id=<?php echo $attempt['id']; ?>" class="btn btn-primary btn-sm">View Details</a>
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
<?php include __DIR__ . '/../layouts/student-footer.php'; ?>