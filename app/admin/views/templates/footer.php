<button class="btn btn-success" type="button">
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    $('.module').on( "mouseenter", function (e) {
        console.log($(this));
        $(this).toggleClass('list-group-item-primary');
    } ).on( "mouseleave", function () {
        $(this).toggleClass('list-group-item-primary');
    } );
</script>
</body>
</html>