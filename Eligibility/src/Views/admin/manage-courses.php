<?php include __DIR__ . '/../layouts/admin-header.php'; ?>

<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Manage Courses</h1>
      </div>
      <div class="col-sm-6">
        <button class="btn btn-primary float-sm-right" data-toggle="modal" data-target="#addCourseModal">Add New Course</button>
      </div>
    </div>
  </div>
</section>

<section class="content">
  <div class="container-fluid">
    <div class="card">
      <div class="card-body">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>S.No</th>
              <th>Course Name</th>
              <th>Course Code</th>
              <th>Status</th> <!-- CHANGED -->
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php
            $rowNumber = 1;
            ?>
            <?php foreach ($courses as $course): ?>
            <tr>
              <td><?php echo $rowNumber; ?></td>
              <td><?php echo htmlspecialchars($course['course_name']); ?></td>
              <td><?php echo htmlspecialchars($course['course_code']); ?></td>
              <td> <!-- NEW -->
                <?php if ($course['status'] == 'active'): ?>
                  <span class="badge badge-success">Active</span>
                <?php else: ?>
                  <span class="badge badge-secondary">Inactive</span>
                <?php endif; ?>
              </td>
              <td>
                <button class="btn btn-info btn-sm edit-btn" 
                        data-id="<?php echo $course['id']; ?>" 
                        data-name="<?php echo htmlspecialchars($course['course_name']); ?>" 
                        data-code="<?php echo htmlspecialchars($course['course_code']); ?>"
                        data-description="<?php echo htmlspecialchars($course['description']); ?>"
                        data-status="<?php echo $course['status']; ?>">Edit</button> <!-- CHANGED -->
                <a href="index.php?action=delete-course&id=<?php echo $course['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
              </td>
            </tr>
            <?php
              $rowNumber++;
            ?>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="index.php?action=add-course" method="post">
        <div class="modal-header">
          <h4 class="modal-title">Add New Course</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Course Name</label>
            <input type="text" class="form-control" name="course_name" required>
          </div>
          <div class="form-group">
            <label>Course Code</label>
            <input type="text" class="form-control" name="course_code" required>
          </div>
          <div class="form-group"> <!-- NEW -->
            <label>Description</label>
            <textarea class="form-control" name="description" rows="3"></textarea>
          </div>
          <div class="form-group"> <!-- NEW -->
            <label>Status</label>
            <select name="status" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Course</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Course Modal -->
<div class="modal fade" id="editCourseModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="index.php?action=update-course" method="post">
        <input type="hidden" name="course_id" id="edit_course_id">
        <div class="modal-header">
          <h4 class="modal-title">Edit Course</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Course Name</label>
            <input type="text" class="form-control" name="course_name" id="edit_course_name" required>
          </div>
          <div class="form-group">
            <label>Course Code</label>
            <input type="text" class="form-control" name="course_code" id="edit_course_code" required>
          </div>
          <div class="form-group"> <!-- NEW -->
            <label>Description</label>
            <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
          </div>
          <div class="form-group"> <!-- NEW -->
            <label>Status</label>
            <select name="status" id="edit_status" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update Course</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>

<!-- CHANGED: Updated JavaScript to handle new fields -->
<script>
$(document).ready(function(){
  $('.edit-btn').on('click', function(){
    // Get data attributes from the button
    var id = $(this).data('id');
    var name = $(this).data('name');
    var code = $(this).data('code');
    var description = $(this).data('description');
    var status = $(this).data('status');

    // Set the values in the modal form
    $('#edit_course_id').val(id);
    $('#edit_course_name').val(name);
    $('#edit_course_code').val(code);
    $('#edit_description').val(description);
    $('#edit_status').val(status);

    // Show the modal
    $('#editCourseModal').modal('show');
  });
});
</script>