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
                                    <input type="text" class="form-control option" id="settingForm<?=$option['id']?>" placeholder="<?=$option['name']?>" value="<?=$option['value']?>" data-option-id="<?=$option['id']?>">
                                </div>
                            <?elseif ($option['type'] == 'checkbox'):?>
                                <div class="form-check">
                                    <label class="form-check-label" for="settingCheckbox<?=$option['id']?>">
	                                    <?=$option['name']?>
                                    </label>
                                    <input class="form-check-input option" type="checkbox" value="" <?=$option['value'] == 'true'?'checked':''?> id="settingCheckbox<?=$option['id']?>" data-option-id="<?=$option['id']?>">
                                </div>
                            <?elseif ($option['type'] == 'enum'):?>
                                <div class="mb-3">
                                    <label class="form-check-label" for="settingEnum<?=$option['id']?>">
	                                    <?=$option['name']?>
                                    </label>
                                    <select class="form-select option" id="settingEnum<?=$option['id']?>" size="3" aria-label="<?=$option['name']?>" multiple data-option-id="<?=$option['id']?>">
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
<div class="alert alert-success alert-dismissible fade show" role="alert" style="display: none">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
    </svg>
    Все настройки успешно сохранены!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<div class="alert alert-warning alert-dismissible fade show" role="alert" style="display: none">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
    </svg>
    К сожалению, не все настройки сохранились успешно!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>