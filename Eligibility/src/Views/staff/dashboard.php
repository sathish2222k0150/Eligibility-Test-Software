<?php include __DIR__ . '/../layouts/staff-header.php'; ?>

<div class="content-header">
  <div class="container-fluid"><h1>Staff Dashboard</h1></div>
</div>

<section class="content">
  <div class="container-fluid">
    <!-- Info boxes -->
    <div class="row">
      <div class="col-lg-4 col-6">
        <div class="small-box bg-info"><div class="inner"><h3><?php echo $stats['my_questions']; ?></h3><p>My Questions</p></div><div class="icon"><i class="fas fa-question-circle"></i></div></div>
      </div>
      <div class="col-lg-4 col-6">
        <div class="small-box bg-success"><div class="inner"><h3><?php echo $stats['my_tests']; ?></h3><p>My Tests</p></div><div class="icon"><i class="fas fa-file-alt"></i></div></div>
      </div>
      <!-- MOST IMPORTANT BOX -->
      <div class="col-lg-4 col-6">
        <div class="small-box bg-danger"><div class="inner"><h3><?php echo $stats['pending_grading']; ?></h3><p>Submissions Pending Grading</p></div><div class="icon"><i class="fas fa-user-clock"></i></div><a href="index.php?action=view-results" class="small-box-footer">View Now <i class="fas fa-arrow-circle-right"></i></a></div>
      </div>
    </div>

    <!-- Quick Actions & Recent Submissions -->
    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header"><h3 class="card-title">Quick Actions</h3></div>
          <div class="card-body">
            <a href="index.php?action=manage-questions" class="btn btn-app bg-secondary"><i class="fas fa-question-circle"></i> Manage Questions</a>
            <a href="index.php?action=manage-tests" class="btn btn-app bg-success"><i class="fas fa-file-alt"></i> Create Test</a>
            <a href="index.php?action=view-results" class="btn btn-app bg-info"><i class="fas fa-poll"></i> View Results</a>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card">
          <div class="card-header"><h3 class="card-title">Latest Submissions to Grade</h3></div>
          <div class="card-body p-0">
            <table class="table">
              <tbody>
                <?php foreach($recent_submissions as $sub): ?>
                <tr>
                  <td><?php echo htmlspecialchars($sub['student_name']); ?></td>
                  <td><?php echo htmlspecialchars($sub['test_title']); ?></td>
                  <td><a href="index.php?action=grade-test&id=<?php echo $sub['id']; ?>" class="btn btn-primary btn-sm">Grade Now</a></td>
                </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../layouts/staff-footer.php'; ?>