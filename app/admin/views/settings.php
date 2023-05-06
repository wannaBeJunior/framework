<form>
	<div class="accordion mt-2 mb-2" id="accordionPanelsStayOpenExample">
		<div class="accordion-item">
			<h2 class="accordion-header" id="panelsStayOpen-headingOne">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="false" aria-controls="panelsStayOpen-collapseOne">
					Безопасность
				</button>
			</h2>
			<div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingOne">
				<div class="accordion-body">
					<div class="mb-3">
						<label for="settingForm" class="form-label">Регулярное выражения для валидации пароля</label>
						<input type="text" class="form-control" id="settingForm" placeholder="Введите регулярное выражение" value="/^(?=.*[0-9])(?=.*[!@#$%^&*])(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z!@#$%^&*]+$/u">
					</div>
					<div class="form-check">
						<label class="form-check-label" for="settingForm2">
							Использовать двухфакторную аутентификацию
						</label>
						<input class="form-check-input" type="checkbox" value="" id="settingForm2">
					</div>
				</div>
			</div>
		</div>
		<div class="accordion-item">
			<h2 class="accordion-header" id="panelsStayOpen-headingTwo">
				<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
					Аутентификация
				</button>
			</h2>
			<div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
				<div class="accordion-body">
					<div class="mb-3">
						<label class="form-check-label" for="settingForm3">
							Обязательные поля при аутентификации/регистрации
						</label>
						<select class="form-select" id="settingForm3" size="3" aria-label="Обязательные поля при аутентификации/регистрации" multiple>
							<option selected>Выберите обязательные поля</option>
							<option value="1">Email</option>
							<option value="2">Логин</option>
							<option value="3">Номер телефона</option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>