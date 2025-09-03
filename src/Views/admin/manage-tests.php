<?php include __DIR__ . '/../layouts/admin-header.php'; ?>

<section class="content-header">
  <div class="container-fluid">
</div>
<div class="row mb-2">
      <div class="col-sm-6"><h1>Manage All Tests</h1></div>
      <!-- NEW: Add Create Test Button -->
      <div class="col-sm-6">
        <button class="btn btn-primary float-sm-right" data-toggle="modal" data-target="#addTestModal">Create New Test</button>
      </div>
    </div>
</section>

<section class="content">
  <div class="container-fluid">
    <!-- Filter Form -->
    <div class="card card-default">
      <div class="card-body">
        <form action="index.php" method="get">
          <input type="hidden" name="action" value="manage-tests">
          <div class="row">
            <div class="col-md-4">
              <select name="course_id" class="form-control">
                <option value="">Filter by Course...</option>
                <?php foreach ($courses as $course): ?>
                  <option value="<?php echo $course['id']; ?>" <?php if(isset($filters['course_id']) && $filters['course_id'] == $course['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($course['course_name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <select name="staff_id" class="form-control">
                <option value="">Filter by Staff...</option>
                <?php foreach ($staff as $s): ?>
                  <option value="<?php echo $s['id']; ?>" <?php if(isset($filters['staff_id']) && $filters['staff_id'] == $s['id']) echo 'selected'; ?>>
                    <?php echo htmlspecialchars($s['full_name']); ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </div>
            <div class="col-md-4">
              <button type="submit" class="btn btn-primary">Filter</button>
              <a href="index.php?action=manage-tests" class="btn btn-secondary">Reset</a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Tests Table -->
    <div class="card">
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Test Title</th>
              <th>Course</th>
              <th>Created By (Staff)</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($tests as $test): ?>
            <tr>
              <td><?php echo htmlspecialchars($test['test_title']); ?></td>
              <td><?php echo htmlspecialchars($test['course_name']); ?></td>
              <td><?php echo htmlspecialchars($test['staff_name']); ?></td>
              <td>
                <?php if ($test['status'] == 'published'): ?>
                  <span class="badge badge-success">Published</span>
                <?php else: ?>
                  <span class="badge badge-secondary">Draft</span>
                <?php endif; ?>
              </td>
              <td>
                <?php if ($test['status'] == 'published'): ?>
                  <a href="index.php?action=update-test-status&id=<?php echo $test['id']; ?>&status=draft" class="btn btn-warning btn-sm">Unpublish</a>
                <?php else: ?>
                  <a href="index.php?action=update-test-status&id=<?php echo $test['id']; ?>&status=published" class="btn btn-success btn-sm">Publish</a>
                <?php endif; ?>
                <a href="index.php?action=delete-test&id=<?php echo $test['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="addTestModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="index.php?action=create-test" method="post">
        <div class="modal-header"><h4 class="modal-title">Create New Test</h4></div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Assign to Staff</label>
                <select name="staff_id" class="form-control" required>
                  <option value="">-- Select Staff --</option>
                  <?php foreach($staff as $s): ?>
                      <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['full_name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Course</label>
                <select name="course_id" class="course-select form-control" required>
                  <option value="">-- Select a Course First --</option>
                  <?php foreach($courses as $course): ?>
                    <option value="<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['course_name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Test Title</label>
            <input type="text" name="test_title" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Duration (in minutes)</label>
            <input type="number" name="duration" class="form-control" required>
          </div>
          <div class="form-group">
            <label>Settings</label>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="randomize_questions" value="1"><label class="form-check-label">Randomize questions</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="one_question_per_page" value="1"><label class="form-check-label">Show one question per page</label></div>
            <div class="form-check"><input class="form-check-input" type="checkbox" name="enable_negative_marking" value="1"><label class="form-check-label">Enable negative marking</label></div>
          </div>
          <div class="form-group">
            <label>Initial Status</label>
            <select name="status" class="form-control"><option value="draft">Draft</option><option value="published">Published</option></select>
          </div>
          <hr>
          <div class="form-group">
            <label>Select Questions for this Test</label>
            <div class="questions-list border p-2" style="height: 200px; overflow-y: scroll;">
              <p class="text-muted">Please select a course to see available questions.</p>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Test</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>
<!-- NEW: Add JavaScript for dynamic question loading -->
<script>
$(document).ready(function(){
  $('.course-select').on('change', function(){
    var courseId = $(this).val();
    var questionsListDiv = $(this).closest('.modal-body').find('.questions-list');
    
    if(courseId) {
      $.ajax({
        url: 'index.php?action=get-questions-for-course',
        type: 'POST',
        data: { course_id: courseId },
        success: function(response){
          questionsListDiv.html(response);
        }
      });
    } else {
      questionsListDiv.html('<p class="text-muted">Please select a course to see available questions.</p>');
    }
  });
});
</script>