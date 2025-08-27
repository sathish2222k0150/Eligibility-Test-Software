<?php include __DIR__ . '/../layouts/admin-header.php'; ?>

<!-- Content Header (Page header) -->
<section class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1>Manage Users</h1>
      </div>
      <div class="col-sm-6">
        <button class="btn btn-success float-sm-right ml-2" data-toggle="modal" data-target="#importUsersModal">Bulk Import (CSV)</button>
        <button class="btn btn-primary float-sm-right" data-toggle="modal" data-target="#addUserModal">Add New User</button>
      </div>
    </div>
  </div>
</section>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">

    <!-- Session Feedback -->
    <?php if (isset($_SESSION['import_feedback'])): ?>
      <div class="alert alert-<?php echo $_SESSION['import_feedback']['type']; ?> alert-dismissible fade show" role="alert">
        <?php echo $_SESSION['import_feedback']['message']; ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php unset($_SESSION['import_feedback']); ?>
    <?php endif; ?>

    <!-- Users Table -->
    <div class="card">
      <div class="card-body">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>S.No</th>
              <th>Full Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
          <?php
            $rowNumber = 1;
          ?>
          <?php foreach ($users as $user): ?>
          <tr>
            <td><?php echo $rowNumber; ?></td>
            <td><?php echo htmlspecialchars($user['full_name']); ?></td>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
            <td><?php echo ucfirst($user['role']); ?></td>
            <td>
              <?php if ($user['role'] === 'staff'): ?>
                <a href="index.php?action=assign-courses&id=<?php echo $user['id']; ?>" class="btn btn-success btn-sm">Assign Courses</a>
              <?php endif; ?>
              <button class="btn btn-info btn-sm edit-btn" 
                      data-id="<?php echo $user['id']; ?>" 
                      data-name="<?php echo htmlspecialchars($user['full_name']); ?>" 
                      data-email="<?php echo htmlspecialchars($user['email']); ?>"
                      data-role="<?php echo $user['role']; ?>">Edit</button>
              <a href="index.php?action=delete-user&id=<?php echo $user['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
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

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="index.php?action=add-user" method="post">
        <div class="modal-header">
          <h4 class="modal-title" id="addUserModalLabel">Add New User</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="add_full_name">Full Name</label>
            <input type="text" class="form-control" id="add_full_name" name="full_name" required>
          </div>
          <div class="form-group">
            <label for="add_email">Email</label>
            <input type="email" class="form-control" id="add_email" name="email" required>
          </div>
          <div class="form-group">
            <label for="add_password">Password</label>
            <input type="password" class="form-control" id="add_password" name="password" required>
          </div>
          <div class="form-group">
            <label for="add_role">Role</label>
            <select class="form-control" id="add_role" name="role" required>
              <option value="staff">Staff</option>
              <option value="student">Student</option>
            </select>
          </div>
          <div class="form-group student-course-field" style="display: none;">
            <label>Assign to Course</label>
            <select class="form-control" name="course_id">
              <option value="">-- Select a Course --</option>
              <?php foreach($courses as $course): ?>
                <option value="<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['course_name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save User</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Bulk Import Users Modal -->
<div class="modal fade" id="importUsersModal" tabindex="-1" role="dialog" aria-labelledby="importUsersModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="index.php?action=import-students-csv" method="post" enctype="multipart/form-data">
        <div class="modal-header">
          <h4 class="modal-title" id="importUsersModalLabel">Bulk Import Students (CSV)</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="student_csv">CSV File</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" name="student_csv" id="student_csv" required accept=".csv">
              <label class="custom-file-label" for="student_csv">Choose file</label>
            </div>
          </div>
          <div class="mt-3">
            <p class="text-muted"><strong>Instructions:</strong></p>
            <ul>
              <li>The file must be a <strong>.csv</strong> file.</li>
              <li>The first row must be a header row.</li>
              <li>Required columns are: `full_name`, `email`, `password`, `course_code`.</li>
              <li>The `course_code` must match an existing course code in the system.</li>
            </ul>
            <a href="/path/to/sample-students.csv" download>Download Sample CSV</a>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-success">Import Students</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="index.php?action=update-user" method="post">
        <input type="hidden" name="user_id" id="edit_user_id">
        <div class="modal-header">
          <h4 class="modal-title" id="editUserModalLabel">Edit User</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label for="edit_full_name">Full Name</label>
            <input type="text" class="form-control" name="full_name" id="edit_full_name" required>
          </div>
          <div class="form-group">
            <label for="edit_email">Email</label>
            <input type="email" class="form-control" name="email" id="edit_email" required>
          </div>
          <div class="form-group">
            <label for="edit_password">New Password</label>
            <input type="password" class="form-control" name="password" id="edit_password" placeholder="Leave blank to keep current password">
          </div>
          <div class="form-group">
            <label for="edit_role">Role</label>
            <select class="form-control" name="role" id="edit_role" required>
              <option value="staff">Staff</option>
              <option value="student">Student</option>
            </select>
          </div>
          <div class="form-group student-course-field" style="display: none;">
            <label>Assign to Course</label>
            <select class="form-control" name="course_id" id="edit_course_id">
              <option value="">-- Select a Course --</option>
              <?php foreach($courses as $course): ?>
                <option value="<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['course_name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Update User</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>


<script>
$(document).ready(function() {

  $('.edit-btn').on('click', function() {

    var userId = $(this).data('id');
    var fullName = $(this).data('name');
    var email = $(this).data('email');
    var role = $(this).data('role');
    $('#edit_user_id').val(userId);
    $('#edit_full_name').val(fullName);
    $('#edit_email').val(email);
    $('#edit_role').val(role);
    $('#editUserModal').modal('show');
  });
  $('.custom-file-input').on('change', function() {
    var fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').html(fileName);
  });
  function toggleCourseField(roleSelect) {
    var courseField = $(roleSelect).closest('.modal-body').find('.student-course-field');
    if ($(roleSelect).val() === 'student') {
      courseField.show();
      courseField.find('select').prop('required', true); // Make course selection mandatory for students
    } else {
      courseField.hide();
      courseField.find('select').prop('required', false);
    }
  }

    $('.role-select').on('change', function() {
    toggleCourseField(this);
  });

  // Trigger on page load in case a modal is pre-filled
  $('.role-select').each(function() {
    toggleCourseField(this);
  });
  
  // Also trigger when opening the edit modal
  $('.edit-btn').on('click', function(){
      // A small delay to ensure modal is ready
      setTimeout(function() {
          toggleCourseField($('#edit_role'));
      }, 200);
  });
});
</script>