<?php

$getAcademicLevels = $academic_levels_view->getAcademicLevels();
$getAcademicAreas = $academic_levels_view->getAcademicAreas();

include_once 'card_select.php';
?>
<?php if (isset($getGroupsByLevelCombination)) : ?>
    <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
            <h3 class="mb-0">Grupos para esta combinación académica</h3>
        </div>
        <!-- Card body -->
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <th>CÓDIGO</th>
                        <th>GRADO</th>
                        <th>TUTOR</th>
                        <th>ACCIÓN</th>
                    </thead>
                    <tbody>
                        <?php foreach ($getGroupsByLevelCombination as $group) : ?>
                            <tr>
                                <td><?= mb_strtoupper($group->group_code); ?></td>
                                <td><?= mb_strtoupper($group->degree); ?></td>
                                <td><?= mb_strtoupper($group->tutor_name); ?></td>
                                <td>
                                    <a href="index.php?controller=academicLevelReport&action=boletas&id_academic_area=<?= $id_academic_area; ?>&id_academic_level=<?= $id_academic_level; ?>&id_campus=<?= $id_campus; ?>&id_section=<?= $id_section; ?>&id_level_combination=<?= $id_level_combination; ?>&id_group=<?= $group->id_group; ?>" class="btn btn-sm btn-primary">Generar boletas</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
<?php endif; ?>

<script src="js/academic_level/academic_level.js"></script>