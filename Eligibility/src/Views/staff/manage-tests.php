<?php include __DIR__ . '/../layouts/staff-header.php'; ?>

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6"><h1>Manage Tests</h1></div>
      <div class="col-sm-6">
        <button class="btn btn-primary float-sm-right" data-toggle="modal" data-target="#addTestModal">Create New Test</button>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Title</th>
              <th>Course</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($tests as $test): ?>
            <tr>
              <td><?php echo htmlspecialchars($test['test_title']); ?></td>
              <td><?php echo htmlspecialchars($test['course_name']); ?></td>
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
                <!-- Add Edit button later if needed -->
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

<!-- Add Test Modal -->
<div class="modal fade" id="addTestModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="index.php?action=create-test" method="post">
        <div class="modal-header"><h4 class="modal-title">Create New Test</h4></div>
        <div class="modal-body">
          <div class="form-group">
            <label>Test Title</label>
            <input type="text" name="test_title" class="form-control" required>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Course</label>
                <select name="course_id" class="course-select form-control" required>
                  <option value="">-- Select a Course --</option>
                  <?php foreach($my_courses as $course): ?>
                    <option value="<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['course_name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Duration (in minutes)</label>
                <input type="number" name="duration" class="form-control" required>
              </div>
            </div>
          </div>
          <!-- NEW: Test Settings -->
          <div class="form-group">
            <label>Settings</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="randomize_questions" value="1">
              <label class="form-check-label">Randomize questions</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="one_question_per_page" value="1">
              <label class="form-check-label">Show one question per page</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" name="enable_negative_marking" value="1">
              <label class="form-check-label">Enable negative marking</label>
            </div>
          </div>
          <div class="form-group">
            <label>Initial Status</label>
            <select name="status" class="form-control">
              <option value="draft">Draft</option>
              <option value="published">Published</option>
            </select>
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
          <button type="submit" class="btn btn-primary">Save Test</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/staff-footer.php'; ?>
<script>
$(document).ready(function(){
  // AJAX to fetch questions when a course is selected
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