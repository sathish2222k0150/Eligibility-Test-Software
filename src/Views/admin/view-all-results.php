<?php include __DIR__ . '/../layouts/admin-header.php'; ?>

<section class="content-header">
  <div class="container-fluid"><h1>All Student Results</h1></div>
</section>

<section class="content">
  <div class="container-fluid">
    <!-- Filter Form -->
    <div class="card card-default">
      <div class="card-header">
        <h3 class="card-title">Filters</h3>
      </div>
      <div class="card-body">
        <form action="index.php" method="get">
          <input type="hidden" name="action" value="view-all-results">
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Course</label>
                <select name="course_id" class="form-control">
                  <option value="">All Courses</option>
                  <?php foreach ($courses as $course): ?>
                    <option value="<?php echo $course['id']; ?>" <?php if(isset($filters['course_id']) && $filters['course_id'] == $course['id']) echo 'selected'; ?>>
                      <?php echo htmlspecialchars($course['course_name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Staff</label>
                <select name="staff_id" class="form-control">
                  <option value="">All Staff</option>
                  <?php foreach ($staff as $s): ?>
                    <option value="<?php echo $s['id']; ?>" <?php if(isset($filters['staff_id']) && $filters['staff_id'] == $s['id']) echo 'selected'; ?>>
                      <?php echo htmlspecialchars($s['full_name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Student</label>
                <select name="student_id" class="form-control">
                  <option value="">All Students</option>
                  <?php foreach ($students as $student): ?>
                    <option value="<?php echo $student['id']; ?>" <?php if(isset($filters['student_id']) && $filters['student_id'] == $student['id']) echo 'selected'; ?>>
                      <?php echo htmlspecialchars($student['full_name']); ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Start Date</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo $filters['start_date'] ?? ''; ?>">
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>End Date</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo $filters['end_date'] ?? ''; ?>">
              </div>
            </div>
          </div>
          <button type="submit" class="btn btn-primary">Apply Filters</button>
          <a href="index.php?action=view-all-results" class="btn btn-secondary">Reset Filters</a>
        </form>
      </div>
    </div>

    <!-- Results Table -->
    <div class="card">
      <div class="card-header">
        <h3 class="card-title">Results (Showing <?php echo count($pagination['results']); ?> of <?php echo $pagination['total']; ?>)</h3>
        <a href="index.php?action=export-results-csv&<?php echo http_build_query($filters); ?>" class="btn btn-success btn-sm float-right">
          Export to CSV
        </a>
      </div>
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Student</th>
              <th>Test</th>
              <th>Course</th>
              <th>Staff</th>
              <th>Date</th>
              <th>Score</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($pagination['results'] as $result): ?>
            <tr>
              <td><?php echo htmlspecialchars($result['student_name']); ?></td>
              <td><?php echo htmlspecialchars($result['test_title']); ?></td>
              <td><?php echo htmlspecialchars($result['course_name']); ?></td>
              <td><?php echo htmlspecialchars($result['staff_name']); ?></td>
              <td><?php echo date('d M Y, H:i', strtotime($result['end_time'])); ?></td>
              <td><?php echo $result['score'] ?? 'N/A'; ?></td>
              <td>
                <?php if($result['status'] == 'completed'): ?>
                  <span class="badge badge-warning">Pending Grade</span>
                <?php else: ?>
                  <span class="badge badge-success">Graded</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="card-footer clearfix">
        <ul class="pagination pagination-sm m-0 float-right">
          <?php
            $totalPages = ceil($pagination['total'] / $pagination['perPage']);
            for ($i = 1; $i <= $totalPages; $i++):
              $filterParams = http_build_query(array_merge($filters, ['page' => $i]));
          ?>
            <li class="page-item <?php if($i == $pagination['page']) echo 'active'; ?>">
              <a class="page-link" href="?action=view-all-results&<?php echo $filterParams; ?>"><?php echo $i; ?></a>
            </li>
          <?php endfor; ?>
        </ul>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>