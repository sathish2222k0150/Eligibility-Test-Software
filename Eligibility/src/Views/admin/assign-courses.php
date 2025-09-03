<?php include __DIR__ . '/../layouts/admin-header.php'; ?>

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Assign Courses to <?php echo htmlspecialchars($staff['full_name']); ?></h1>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <form action="index.php?action=update-assigned-courses" method="post">
          <input type="hidden" name="staff_id" value="<?php echo $staff['id']; ?>">
          <div class="form-group">
            <?php foreach ($all_courses as $course): ?>
              <div class="form-check">
                <input class="form-check-input" type="checkbox" name="course_ids[]" value="<?php echo $course['id']; ?>"
                  <?php if (in_array($course['id'], $assigned_courses)): ?>
                    checked
                  <?php endif; ?>
                >
                <label class="form-check-label"><?php echo htmlspecialchars($course['course_name']); ?></label>
              </div>
            <?php endforeach; ?>
          </div>
          <button type="submit" class="btn btn-primary">Save Assignments</button>
          <a href="index.php?action=manage-users" class="btn btn-secondary">Back to Users</a>
        </form>
      </div>
    </div>
  </div>
</section>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>