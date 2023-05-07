<form>
	<div class="accordion mt-2 mb-2" id="accordionPanelsStayOpenExample">
        <?foreach ($result['options'] as $section => $options):?>
            <div class="accordion-item">
                <h2 class="accordion-header" id="panelsStayOpen-heading<?=$section?>">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapse<?=$section?>" aria-expanded="false" aria-controls="panelsStayOpen-collapse<?=$section?>">
                        <?=$section?>
                    </button>
                </h2>
                <div id="panelsStayOpen-collapse<?=$section?>" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-heading<?=$section?>">
                    <div class="accordion-body">
                        <?foreach ($options as $option):?>
                            <?if($option['type'] == 'text'):?>
                                <div class="mb-3">
                                    <label for="settingForm<?=$option['id']?>" class="form-label"><?=$option['name']?></label>
                                    <input type="text" class="form-control" id="settingForm<?=$option['id']?>" placeholder="<?=$option['name']?>" value="<?=$option['value']?>" data-option-id="<?=$option['id']?>">
                                </div>
                            <?elseif ($option['type'] == 'checkbox'):?>
                                <div class="form-check">
                                    <label class="form-check-label" for="settingCheckbox<?=$option['id']?>">
	                                    <?=$option['name']?>
                                    </label>
                                    <input class="form-check-input" type="checkbox" value="" <?=$option['value']?'checked':''?> id="settingCheckbox<?=$option['id']?>" data-option-id="<?=$option['id']?>">
                                </div>
                            <?elseif ($option['type'] == 'enum'):?>
                                <div class="mb-3">
                                    <label class="form-check-label" for="settingEnum<?=$option['id']?>">
	                                    <?=$option['name']?>
                                    </label>
                                    <select class="form-select" id="settingEnum<?=$option['id']?>" size="3" aria-label="<?=$option['name']?>" multiple data-option-id="<?=$option['id']?>">
                                        <?foreach ($option['values'] as $value):?>
                                            <option value="<?=$value['id']?>" <?=$value['selected']?'selected':''?>><?=$value['enum_value']?></option>
                                        <?endforeach;?>
                                    </select>
                                </div>
                            <?endif;?>
                        <?endforeach;?>
                    </div>
                </div>
            </div>
        <?endforeach;?>
	</div>
</form>