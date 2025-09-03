<?php
// Include the header
include __DIR__ . '/../layouts/student-header.php';
?>

<!-- Main content -->
<section class="content">
  <div class="container-fluid pt-3"> <!-- Added pt-3 for padding-top -->
    <div class="row">
        <div class="col-12">
            <h1>Available Tests</h1>
        </div>
    </div>
    <div class="row">
      <?php if (!empty($tests)): ?>
        <?php foreach($tests as $test): ?>
          <div class="col-md-6">
            <div class="card">
              <div class="card-body">
                <h5 class="card-title font-weight-bold"><?php echo htmlspecialchars($test['test_title']); ?></h5>
                <p class="card-text">Course: <?php echo htmlspecialchars($test['course_name']); ?> | Duration: <?php echo $test['duration_minutes']; ?> mins</p>
                <a href="index.php?action=show-instructions&id=<?php echo $test['id']; ?>" class="btn btn-primary">Start Test</a>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <div class="col-12">
            <div class="alert alert-info">
                No tests are available at the moment.
            </div>
        </div>
      <?php endif; ?>
    </div>
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<?php
// Include the footer
include __DIR__ . '/../layouts/student-footer.php';
?>