<h1><?= $headline ?></h1>
<?= flashdata() ?>
<div class="card">
    <div class="card-heading">
        Weekly Schedule Details
    </div>
    <div class="card-body">
        <div class="text-right mb-3">
            <?= anchor($back_url, 'Back', array('class' => 'button alt')) ?>
            <?= anchor(BASE_URL.'weekly_schedules/create/'.$update_id, 'Edit', array('class' => 'button')) ?>
            <?= anchor('weekly_schedules/delete_conf/'.$update_id, 'Delete',  array('class' => 'button danger')) ?>
        </div>
        
        <div class="detail-grid">
            <div class="detail-row">
                <div class="detail-label">Employee Name</div>
                <div class="detail-value"><?= out($employee_name) ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Work Week</div>
                <div class="detail-value">
                    <?= out($work_week_formatted) ?>
                    <?php if (!empty($work_week_range)): ?>
                        <br><small style="color: #666;"><?= out($work_week_range) ?></small>
                    <?php endif; ?>
                </div>
            </div>
            <div class="detail-block">
                <div class="detail-label">Tasks</div>
                <div class="detail-content"><?= nl2br(out($tasks)) ?></div>
            </div>
        </div>
    </div>
</div>
