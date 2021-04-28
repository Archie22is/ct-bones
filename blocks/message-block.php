<?php if (!empty($content)) : ?>
  <div class="message-block<?php if (!empty($class)) : echo ' ' . $class; endif; ?>">
    <div class="message-block__container">
      <?php echo $content; ?>
    </div>
  </div>
<?php endif; ?>
