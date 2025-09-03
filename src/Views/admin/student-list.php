<?php include __DIR__ . '/../layouts/admin-header.php'; ?>

<section class="content-header">
  <div class="container-fluid"><h1>Student List</h1></div>
</section>

<section class="content">
  <div class="container-fluid">
    <!-- Filter Form -->
    <div class="card card-default">
      <div class="card-header"><h3 class="card-title">Filters</h3></div>
      <div class="card-body">
        <form action="index.php" method="get">
          <input type="hidden" name="action" value="student-list">
          <div class="row">

            <!-- Course Filter Dropdown (Corrected) -->
            <div class="col-md-3">
              <div class="form-group">
                <select name="course_id" class="form-control">
                  <option value="">Filter by Course...</option>
                  <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['id']; ?>" <?php if(isset($filters['course_id']) && $filters['course_id'] == $course['id']) echo 'selected'; ?>>
                      <?php echo htmlspecialchars($course['course_name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <!-- Staff Filter Dropdown (Corrected) -->
            <div class="col-md-3">
              <div class="form-group">
                <select name="staff_id" class="form-control">
                  <option value="">Filter by Staff...</option>
                  <?php foreach ($staff as $s): ?>
                    <option value="<?php echo $s['id']; ?>" <?php if(isset($filters['staff_id']) && $filters['staff_id'] == $s['id']) echo 'selected'; ?>>
                      <?php echo htmlspecialchars($s['full_name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

            <!-- Status Filter Dropdown -->
            <div class="col-md-3">
              <div class="form-group">
                <select name="status" class="form-control">
                  <option value="">Filter by Result Status...</option>
                  <option value="pending" <?php if(isset($filters['status']) && $filters['status'] == 'pending') echo 'selected'; ?>>Pending</option>
                  <option value="graded" <?php if(isset($filters['status']) && $filters['status'] == 'graded') echo 'selected'; ?>>Graded</option>
                </select>
              </div>
            </div>

            <div class="col-md-3">
              <button type="submit" class="btn btn-primary">Filter</button>
              <a href="index.php?action=student-list" class="btn btn-secondary">Reset</a>
            </div>

          </div>
        </form>
      </div>
    </div>

    <!-- Student Table -->
    <div class="card">
      <div class="card-body">
        <table class="table table-bordered">
        <thead>
          <tr>
            <th>S.No</th>
            <th>Full Name</th>
            <th>Email</th>
            <!-- CHANGE 1: The header is now plural -->
            <th>Enrolled Course(s)</th>
            <th>Latest Status</th>
          </tr>
        </thead>
        <tbody>
          <?php $rowNumber = ($pagination['page'] - 1) * $pagination['perPage'] + 1; ?>
          <?php foreach($pagination['students'] as $student): ?>
          <tr>
            <td><?php echo $rowNumber++; ?></td>
            <td><?php echo htmlspecialchars($student['full_name']); ?></td>
            <td><?php echo htmlspecialchars($student['email']); ?></td>
            <td>
              <!-- CHANGE 2: Use the new 'enrolled_courses' variable -->
              <?php echo htmlspecialchars($student['enrolled_courses'] ?? 'Not Enrolled'); ?>
            </td>
            <td>
              <?php if ($student['latest_attempt_status'] == 'completed'): ?>
                <span class="badge badge-warning">Pending</span>
              <?php elseif ($student['latest_attempt_status'] == 'graded'): ?>
                <span class="badge badge-success">Graded</span>
              <?php else: ?>
                <span class="badge badge-info">No Attempts</span>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      </div>
      <!-- Pagination Links -->
      <div class="card-footer clearfix">
        <ul class="pagination pagination-sm m-0 float-right">
          <?php
            $totalPages = ceil($pagination['total'] / $pagination['perPage']);
            for ($i = 1; $i <= $totalPages; $i++):
              $filterParams = http_build_query(array_merge($filters, ['page' => $i]));
          ?>
            <li class="page-item <?php if($i == $pagination['page']) echo 'active'; ?>">
              <a class="page-link" href="?action=student-list&<?php echo $filterParams; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>