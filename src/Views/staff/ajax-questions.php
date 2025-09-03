<?php foreach($questions as $question): ?>
<div class="form-check">
  <input class="form-check-input" type="checkbox" name="question_ids[]" value="<?php echo $question['id']; ?>">
  <label class="form-check-label"><?php echo htmlspecialchars($question['question_text']); ?></label>
</div>
<?php endforeach; ?>