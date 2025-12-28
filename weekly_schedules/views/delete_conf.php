<h1><?= $headline ?></h1>
<div class="card">
    <div class="card-heading">
        Confirmation Required
    </div>
    <div class="card-body">
        <p>Are you sure?</p>
        <p>You are about to delete the weekly schedule for <strong><?= out($employee_name) ?></strong> for <strong><?= out($work_week_formatted) ?></strong>. This cannot be undone. Do you really want to do this?</p>
        
        <?php
        echo form_open($form_location);
        echo '<div class="text-center">';
        echo anchor($cancel_url, 'Cancel', array('class' => 'button alt'));
        echo form_submit('submit', 'Yes - Delete Now', array('class' => 'danger'));
        echo form_close();
        echo '</div>';
        ?>
    </div>
</div>
