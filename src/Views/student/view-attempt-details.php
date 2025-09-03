<?php include __DIR__ . '/../layouts/student-header.php'; ?>
<section class="content-header">
  <div class="container-fluid"><h1>Result Details</h1></div>
</section>
<section class="content">
  <div class="container-fluid">
    <?php foreach($details as $item): ?>
      <div class="card">
        <div class="card-header">
          <strong>Question (<?php echo $item['points']; ?> Points)</strong>
        </div>
        <div class="card-body">
          <p><?php echo htmlspecialchars($item['question_text']); ?></p>
          <?php if ($item['attachment_path']): ?>
            <img src="<?php echo htmlspecialchars($item['attachment_path']); ?>" class="img-fluid mb-3" style="max-height: 200px;">
          <?php endif; ?>
          <hr>
          <p><strong>Your Answer:</strong></p>
          <?php if($item['question_type'] === 'multiple_choice'): ?>
            <?php 
              $options_array = explode('|||', $item['options']);
              $option_map = [];
              foreach($options_array as $opt) { list($id, $text) = explode(':::', $opt, 2); $option_map[$id] = $text; }
              $student_answer_text = $option_map[$item['selected_option_id']] ?? '<i>Not Answered</i>';
              $is_correct = ($item['selected_option_id'] == $item['correct_option_id']);
            ?>
            <p class="<?php echo $is_correct ? 'text-success' : 'text-danger'; ?>"><?php echo htmlspecialchars($student_answer_text); ?></p>
            <?php if(!$is_correct): ?>
              <p class="text-success"><strong>Correct Answer:</strong> <?php echo htmlspecialchars($option_map[$item['correct_option_id']]); ?></p>
            <?php endif; ?>
          <?php else: // Short Answer ?>
            <blockquote class="blockquote"><p class="mb-0"><em><?php echo nl2br(htmlspecialchars($item['answer_text'])); ?></em></p></blockquote>
            <p><strong>Marks Awarded: <?php echo $item['marks_awarded'] ?? 0; ?> / <?php echo $item['points']; ?></strong></p>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
    <a href="index.php?action=my-results" class="btn btn-secondary mb-4">Back to Results</a>
  </div>
</section>
<?php include __DIR__ . '/../layouts/student-footer.php'; ?>