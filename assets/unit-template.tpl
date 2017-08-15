<?php foreach ($sections as $section => $properties): ?>
[<?= $this->e($section); ?>]
<?php foreach ($properties as $property => $value): ?>
<?= $this->e($property); ?>=<?= $this->e($value); ?>
<?php endforeach; ?>

<?php endforeach; ?>