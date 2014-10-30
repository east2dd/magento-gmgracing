<?php 
    $makes = get_terms( 'project-model', array( 'parent' => 0 ) );
?>
<div class="row">
    <div class="col-sm-4">
        <h2 class="page-title">PROJECTS</h2>
    </div>
    <div class="col-sm-3 col-sm-offset-5">
        <div class="refine-vehicle">
            <div class="text-right">
                <a href="#" class="link-refine-vehicle" data-toggle="popover"><span class="glyphicon glyphicon-chevron-down"></span> REFINE VEHICLE</a>
            </div>
            <div class="popover bottom col-sm-12" id="form-refine-vehicle">
                <div class="arrow"></div>
                <div class="popover-content">
                    <form class="form-horizontal col-sm-12" method="post">
                        <div class="row text-right"><small><a href="#" class="link-refine-vehicle">CLOSE <span class="glyphicon glyphicon-remove"></span></a></small></div>
                        <div class="form-group">
                            <label>MAKE</label>
                            <div>
                                <select name="make" class="form-control make-selector">
                                    <option></option>
                                    <?php
                                        foreach($makes as $make)
                                        {
                                            ?>
                                            <option value="<?php echo $make->slug; ?>"><?php echo $make->name; ?></option>
                                            <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>
                         <div class="form-group">
                            <label>MODEL</label>
                            <div>
                                <select name="model" class="form-control model-selector">
                                    <option></option>
                                </select>
                                <?php
                                    foreach($makes as $make)
                                    {
                                ?>
                                    <select name="model" class="form-control model-selector hidden" id="make-<?php echo $make->slug; ?>-models">
                                    <option></option>
                                    <?php
                                            $model_ids = get_term_children( $make->term_id, 'project-model' );
                                            foreach($model_ids as $model_id)
                                            {
                                                $model = get_term_by('id', $model_id, 'project-model');
                                            ?>
                                            <option value="<?php echo $model->slug; ?>"><?php echo $model->name; ?></option>
                                            <?php
                                            }
                                    ?>
                                    </select>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary">SUBMIT <span class="glyphicon glyphicon-play"></span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>