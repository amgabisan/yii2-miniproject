<?php
    use yii\helpers\Html;
    use app\assets\DatatablesAsset;
    use app\assets\AlertAsset;

    DatatablesAsset::register($this);
    AlertAsset::register($this);

    $this->title = 'Survey List';
?>

<div class="page-header text-center">
    <h3>Survey List</h3>
</div>
<a href="/survey/create" class="btn btn-primary pull-right">Create Survey</a>
<br><br>
<div class="page-content">
    <table class="table table-bordered dt-responsive nowrap" style="width:100%" id="questionnaire-list">
        <thead>
            <th class="text-center">Survey Name</th>
            <th class="text-center">Answer Count</th>
            <th class="text-center">Answer Link</th>
            <th class="text-center">Time Updated</th>
            <th class="text-center">Action</th>
        </thead>
        <tbody>
            <?php foreach ($records as $row) { ?>
            <tr>
                <td><?= $row['name'] ?></td>
                <td class="text-center"><?= $row['questionnaire_count'] ?></td>
                <td>
                    <a href="/<?= $row['id'] ?>">
                        http://amg.com/<?= $row['id'] ?>
                    </a>
                </td>
                <td class="text-center"><?= $row['up_time'] ?></td>
                <td>
                    <ul class="list-inline text-center">
                        <?php
                            $disabled = '';
                            $deleteBtn = 'delete-btn';
                            if ($row['questionnaire_count'] != 0) {
                                $disabled = 'disabled="disabled"';
                                $deleteBtn .= ' cant-delete';
                            }
                        ?>
                        <li>
                            <a href="/survey/edit/<?= $row['id'] ?>" class="btn btn-primary" <?= $disabled ?>>
                                <span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit
                            </a>
                        </li>
                        <li>
                            <button type="button" class="btn btn-danger <?= $deleteBtn ?>" id="<?= $row['id'] ?>" <?= $disabled ?>>
                                <span class="glyphicon glyphicon-trasht" aria-hidden="true"></span> Delete
                            </button>
                        </li>
                    </ul>
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
