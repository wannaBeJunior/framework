<button class="btn btn-success option-save" type="button">
	Сохранить
</button>
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebar" aria-labelledby="sidebarLabel">
	<div class="offcanvas-header">
		<h5 class="offcanvas-title" id="sidebarLabel">Модули</h5>
		<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
	</div>
	<div class="offcanvas-body">
		<ol class="list-group list-group-numbered">
            <?foreach ($result['modules'] as $module => $description):?>
                <li class="list-group-item d-flex justify-content-between align-items-start module <?=$module==$result['current_module']?'list-group-item-success':''?>">
                    <div class="ms-2 me-auto">
                        <div class="fw-bold"><?=$description['name']?></div>
	                    <?=$description['description']?>
                    </div>
                </li>
            <?endforeach;?>
		</ol>
	</div>
</div>
</div>
<?$this->includeJs("https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js");?>
<?$this->includeJs("https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js");?>
<?$this->includeJs("/app/admin/assets/scripts/admin.js");?>
</body>
</html>