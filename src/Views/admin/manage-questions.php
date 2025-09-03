<?php include __DIR__ . '/../layouts/admin-header.php'; ?>

<section class="content-header">
  <div class="container-fluid"><h1>Manage All Questions</h1></div>
</section>

<section class="content">
  <div class="container-fluid">
    <!-- Filter Form -->
    <div class="card card-default">
      <div class="card-body">
        <form action="index.php" method="get">
          <input type="hidden" name="action" value="manage-questions">
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
              <button type="submit" class="btn btn-primary">Apply</button>
              <a href="index.php?action=manage-questions" class="btn btn-secondary">Reset</a>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Questions Table -->
<div class="card">
  <div class="card-header">
    <button class="btn btn-primary float-right" data-toggle="modal" data-target="#addQuestionModal">Add New Question</button>
  </div>
  <div class="card-body">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th>S.No</th>
          <th>Course</th>
          <th>Staff</th>
          <th>Question</th>
          <th>Type</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
          $rowNumber = 1; 
        ?>
        <?php foreach ($questions as $q): ?>
        <tr>
          <td><?php echo $rowNumber; ?></td>
          <td><?php echo htmlspecialchars($q['course_name']); ?></td>
          <td><?php echo htmlspecialchars($q['staff_name']); ?></td>
          <td><?php echo htmlspecialchars(substr($q['question_text'], 0, 70)) . '...'; ?></td>
          <td><?php echo str_replace('_', ' ', $q['question_type']); ?></td>
          <td>
            <button class="btn btn-info btn-sm edit-btn" data-id="<?php echo $q['id']; ?>">Edit</button>
            <a href="index.php?action=delete-question&id=<?php echo $q['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</a>
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

<!-- Add Question Modal (Corrected) -->
<div class="modal fade" id="addQuestionModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="index.php?action=add-question" method="post" enctype="multipart/form-data">
        <div class="modal-header"><h4 class="modal-title">Add New Question</h4></div>
        <div class="modal-body">
          <div class="form-group">
              <label>Assign to Staff</label>
              <select name="staff_id" class="form-control" required>
                  <option value="">-- Select Staff --</option>
                  <?php foreach($staff as $s): ?>
                      <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['full_name']); ?></option>
                  <?php endforeach; ?>
              </select>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Course</label>
                <select name="course_id" class="form-control" required>
                  <option value="">-- Select a Course --</option>
                  <!-- FIX: Use the '$courses' variable, not '$my_courses' -->
                  <?php foreach($courses as $course): ?>
                    <option value="<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['course_name']); ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Question Type</label>
                <select name="question_type" id="question_type" class="form-control" required>
                  <option value="short_answer">Short Answer</option>
                  <option value="multiple_choice">Multiple Choice</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label>Question Text</label>
            <textarea name="question_text" class="form-control" rows="3" required></textarea>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Points</label>
                <input type="number" name="points" class="form-control" value="1" min="1" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Negative Points for Incorrect</label>
                <input type="number" name="negative_points" class="form-control" value="0" min="0" step="0.25" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Difficulty</label>
                <select name="difficulty" class="form-control" required>
                  <option value="easy">Easy</option>
                  <option value="medium" selected>Medium</option>
                  <option value="hard">Hard</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="attachment">Attachment (Optional Image)</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" name="attachment" id="attachment" accept="image/jpeg, image/png, image/gif">
              <label class="custom-file-label" for="attachment">Choose file</label>
            </div>
          </div>
          <div id="mcq-options" style="display:none;">
            <hr><h5>Multiple Choice Options</h5>
            <p>Enter the options below and select the correct one.</p>
            <?php for($i = 0; $i < 4; $i++): ?>
            <div class="input-group mb-3">
              <div class="input-group-prepend"><div class="input-group-text"><input type="radio" name="is_correct" value="<?php echo $i; ?>"></div></div>
              <input type="text" class="form-control" name="options[]" placeholder="Option <?php echo $i+1; ?>">
            </div>
            <?php endfor; ?>
          </div>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save Question</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="editQuestionModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form id="editQuestionForm" action="index.php?action=update-question" method="post" enctype="multipart/form-data">
        <input type="hidden" name="question_id" id="edit_question_id">
        <div class="modal-header"><h4 class="modal-title">Edit Question</h4></div>
        <div class="modal-body">
           <div class="form-group">
              <label>Assign to Staff</label>
              <select name="staff_id" id="edit_staff_id" class="form-control" required>
                  <?php foreach($staff as $s): ?>
                      <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['full_name']); ?></option>
                  <?php endforeach; ?>
              </select>
          </div>
          <div class="form-group">
            <label>Course</label>
            <select name="course_id" id="edit_course_id" class="form-control" required>
              <!-- FIX: Use the '$courses' variable, not '$my_courses' -->
              <?php foreach($courses as $course): ?>
                <option value="<?php echo $course['id']; ?>"><?php echo htmlspecialchars($course['course_name']); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
                <div class="form-group">
            <label>Question Type</label>
            <select name="question_type" id="edit_question_type" class="form-control" required>
              <option value="short_answer">Short Answer</option>
              <option value="multiple_choice">Multiple Choice</option>
            </select>
          </div>
          <div class="form-group">
            <label>Question Text</label>
            <textarea name="question_text" id="edit_question_text" class="form-control" rows="3" required></textarea>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>Points</label>
                <input type="number" name="points" id="edit_points" class="form-control" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Negative Points for Incorrect</label>
                <input type="number" name="negative_points" class="form-control" value="0" min="0" step="0.25" required>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Difficulty</label>
                <select name="difficulty" id="edit_difficulty" class="form-control" required>
                  <option value="easy">Easy</option>
                  <option value="medium">Medium</option>
                  <option value="hard">Hard</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label for="edit_attachment">New Attachment (Optional)</label>
            <div class="custom-file"><input type="file" class="custom-file-input" name="attachment" id="edit_attachment" accept="image/jpeg, image/png, image/gif"><label class="custom-file-label" for="edit_attachment">Choose file</label></div>
            <small class="form-text text-muted">Leave blank to keep the current attachment (if any).</small>
          </div>
          <div id="edit_mcq-options" style="display:none;">
            <hr><h5>Multiple Choice Options</h5>
            <?php for($i = 0; $i < 4; $i++): ?>
            <div class="input-group mb-3">
              <div class="input-group-prepend"><div class="input-group-text"><input type="radio" name="is_correct" value="<?php echo $i; ?>"></div></div>
              <input type="text" class="form-control" name="options[]">
            </div>
            <?php endfor; ?>
          </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Update Question</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php include __DIR__ . '/../layouts/admin-footer.php'; ?>
<script>
$(document).ready(function() {
  $('#question_type').on('change', function() {
    if ($(this).val() === 'multiple_choice') {
      $('#mcq-options').show();
      $('#mcq-options input[type="text"]').prop('required', true);
      $('#mcq-options input[type="radio"]').prop('required', true);
    } else {
      $('#mcq-options').hide();
      $('#mcq-options input[type="text"]').prop('required', false);
      $('#mcq-options input[type="radio"]').prop('required', false);
    }
  }).trigger('change'); // Trigger on page load to set initial state
  $('.custom-file-input').on('change', function() {
    var fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').html(fileName);
  });
   $('.edit-btn').on('click', function() {
    var questionId = $(this).data('id');

    // Fetch question data via AJAX
    $.ajax({
      url: 'index.php?action=edit-question',
      type: 'POST',
      data: { id: questionId },
      dataType: 'json',
      success: function(response) {
        // Populate the general form fields
        $('#edit_question_id').val(response.id);
        $('#edit_course_id').val(response.course_id);
        $('#edit_question_type').val(response.question_type);
        $('#edit_question_text').val(response.question_text);
        $('#edit_points').val(response.points);
        $('#edit_negative_points').val(response.negative_points);
        $('#edit_difficulty').val(response.difficulty);
        
        // Handle MCQ options
        if (response.question_type === 'multiple_choice') {
          $('#edit_mcq-options').show();
          // Clear previous options
          $('#edit_mcq-options input[type="text"]').val('');
          $('#edit_mcq-options input[type="radio"]').prop('checked', false);
          
          // Populate options
          if (response.options) {
            response.options.forEach(function(option, index) {
              var optionInput = $('#edit_mcq-options input[name="options[]"]').eq(index);
              var radioInput = $('#edit_mcq-options input[name="is_correct"]').eq(index);
              
              optionInput.val(option.option_text);
              if (option.is_correct == 1) {
                radioInput.prop('checked', true);
              }
            });
          }
        } else {
          $('#edit_mcq-options').hide();
        }

        // Show the modal
        $('#editQuestionModal').modal('show');
      }
    });
  });

  // Show/hide MCQ options in edit modal when type is changed
  $('#edit_question_type').on('change', function() {
    if ($(this).val() === 'multiple_choice') {
      $('#edit_mcq-options').show();
    } else {
      $('#edit_mcq-options').hide();
    }
  });
});
</script>