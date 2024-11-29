<div class="card mb-4">
    <!-- Card header -->
    <div class="card-header">
        <h3 class="mb-0">Generar boletas por nivel académico</h3>
    </div>
    <!-- Card body -->
    <div class="card-body">
        <?php
        if (isset($_GET['id_academic_area']) && isset($_GET['id_academic_level']) && isset($_GET['id_campus']) && isset($_GET['id_section'])) :
            $id_academic_area = $_GET['id_academic_area'];
            $id_academic_level = $_GET['id_academic_level'];
            $id_campus = $_GET['id_campus'];
            $id_section = $_GET['id_section'];
            $id_period = $_GET['id_period'];

            $getCampusByAcademicLevel = $academic_levels_view->getCampusByAcademicLevel($id_academic_level);
            $getSectionsByCampus = $academic_levels_view->getSectionsByCampus($id_campus, $id_academic_level);

            $getLevelCombinations = $academic_levels_view->getLevelCombinations($id_academic_area, $id_academic_level, $id_campus, $id_section);
            if (!empty($getLevelCombinations)) {
                $id_level_combination = $getLevelCombinations = $getLevelCombinations[0]->id_level_combination;

                $getGroupsByLevelCombination = $academic_levels_view->getGroupsByLevelCombination($id_level_combination);
                $getPeriodsByLevelCombination = $academic_levels_view->getPeriodsByLevelCombination($id_level_combination);
            }
        ?>
            <div class="row">
                <!-- -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-control-label" for="slct_academic_area">* Elija un área académica</label>
                        <form>
                            <select class="form-control" name="slct_academic_area" id="slct_academic_area">
                                <option selected value="" disabled>Elija una opción</option>
                                <?php foreach ($getAcademicAreas as $academicArea) : ?>
                                    <?php if ($academicArea->id_academic_area == $id_academic_area) : ?>
                                        <option selected value="<?= $academicArea->id_academic_area; ?>"><?= mb_strtoupper($academicArea->name_academic_area); ?></option>
                                    <?php else : ?>
                                        <option value="<?= $academicArea->id_academic_area; ?>"><?= mb_strtoupper($academicArea->name_academic_area); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                </div>
                <!-- -->
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-control-label" for="slct_academic_level">* Elija un nivel académico</label>
                        <form>
                            <select class="form-control" name="slct_academic_level" id="slct_academic_level">
                                <option selected value="" disabled>Elija una opción</option>
                                <?php foreach ($getAcademicLevels as $academic_level) : ?>
                                    <?php if ($academic_level->id_academic_level == $id_academic_level) : ?>
                                        <option selected value="<?= $academic_level->id_academic_level; ?>"><?= mb_strtoupper($academic_level->academic_level); ?></option>
                                    <?php else : ?>
                                        <option value="<?= $academic_level->id_academic_level; ?>"><?= mb_strtoupper($academic_level->academic_level); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="form-control-label" for="slct_campus">* Elija un campus</label>
                        <form>
                            <select class="form-control" name="slct_campus" id="slct_campus">
                                <option selected value="" disabled>Elija una opción</option>
                                <?php foreach ($getCampusByAcademicLevel as $campus) : ?>
                                    <?php if ($campus->id_campus == $id_campus) : ?>
                                        <option selected value="<?= $campus->id_campus; ?>"><?= mb_strtoupper($campus->campus_name); ?></option>
                                    <?php else : ?>
                                        <option value="<?= $campus->id_campus; ?>"><?= mb_strtoupper($campus->campus_name); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-control-label" for="slct_section">* Elija una sección</label>
                        <form>
                            <select class="form-control" name="slct_section" id="slct_section">
                                <option selected value="" disabled>Elija una opción</option>
                                <?php foreach ($getSectionsByCampus as $section) : ?>
                                    <?php if ($section->id_section == $id_section) : ?>
                                        <option selected value="<?= $section->id_section; ?>"><?= mb_strtoupper($section->section); ?></option>
                                    <?php else : ?>
                                        <option value="<?= $section->id_section; ?>"><?= mb_strtoupper($section->section); ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-control-label" for="slct_period">* Elija un periodo</label>
                        <form>
                            <select class="form-control" name="slct_period" id="slct_period">
                                <option selected value="" disabled>Elija una opción</option>
                                <?php foreach ($getPeriodsByLevelCombination as $period) : ?>
                                    <?php if ($period->id_period_calendar == $id_period) : ?>
                                        <option selected value="<?= $period->id_period_calendar; ?>"><?= $period->no_period ?></option>
                                    <?php else : ?>
                                        <option value="<?= $period->id_period_calendar; ?>"><?= $period->no_period ?></option>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        <?php else : ?>
            <!-- Form groups used in grid -->
            <div class="row">
                <!-- -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-control-label" for="slct_academic_area">* Elija un área académica</label>
                        <form>
                            <select class="form-control" name="slct_academic_area" id="slct_academic_area">
                                <option selected value="" disabled>Elija una opción</option>
                                <?php foreach ($getAcademicAreas as $academicArea) : ?>
                                    <option value="<?= $academicArea->id_academic_area; ?>"><?= mb_strtoupper($academicArea->name_academic_area); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                </div>
                <!-- -->
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-control-label" for="slct_academic_level">* Elija un nivel académico</label>
                        <form>
                            <select class="form-control" name="slct_academic_level" id="slct_academic_level" disabled>
                                <option selected value="" disabled>Elija una opción</option>
                                <?php foreach ($getAcademicLevels as $academic_level) : ?>
                                    <option value="<?= $academic_level->id_academic_level; ?>"><?= mb_strtoupper($academic_level->academic_level); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-control-label" for="slct_campus">* Elija un campus</label>
                        <form>
                            <select class="form-control" name="slct_campus" id="slct_campus">
                                <option selected value="" disabled>Elija una opción</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-control-label" for="slct_section">* Elija una sección</label>
                        <form>
                            <select class="form-control" name="slct_section" id="slct_section">
                                <option selected value="" disabled>Elija una opción</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-control-label" for="slct_period">* Elija un periodo</label>
                        <form>
                            <select class="form-control" name="slct_period" id="slct_period">
                                <option selected value="" disabled>Elija una opción</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>