<h1><?= $headline ?></h1>
<?= validation_errors() ?>
<div class="card">
    <div class="card-heading">
        Weekly Schedule Details
    </div>
    <div class="card-body">
        <?php
        echo form_open($form_location);
        
        echo form_label('Employee Name');
        echo form_input('employee_name', $employee_name, ["placeholder" => "Enter Employee Name"]);
        
        echo form_label('Work Week');
        echo form_week('work_week', $work_week);
        
        echo form_label('Tasks');
        echo form_textarea('tasks', $tasks, ["placeholder" => "Enter Tasks for This Week", "rows" => 6]);

        echo '<div class="text-center">';
        echo anchor($cancel_url, 'Cancel', ['class' => 'button alt']);
        echo form_submit('submit', 'Submit');
        echo form_close();
        echo '</div>';
        ?>
    </div>
</div>
