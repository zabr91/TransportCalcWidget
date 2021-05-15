

<a href = "?page=<?= $_GET['page'] ?>" class="button button-primary">Назад</a>

<form action="" method="POST">

<table class="form-table" role="presentation">
	<tbody>
		<tr><th scope="row">Заголовок</th>
		<td>		
		<input type="text" name="title" value="<?= isset($values[0]->title) ? $values[0]->title :  "" ?>">
		</td>
		<td>
			Используется только админпанели
		</td>
	    </tr>

		<tr><th scope="row">Описание</th>
		<td>		
		<input type="text" name="description" value="<?= isset($values[0]->description) ? $values[0]->description : "" ?>">
		</td>
		<td>
			Используется только админпанели
		</td>
	    </tr>

		<tr><th scope="row">Дистанция до которой действует тариф (км)</th>
		<td>		
		<input type="text" name="distance" value="<?= isset($values[0]->distance) ? $values[0]->distance : "" ?>">
		</td>
		<td>
			Например, если мы поставим 200, то тариф будет действувать до 200 км
		</td>
	    </tr>

		<tr><th scope="row">Вес груза (т)</th>
		<td>		
		<input type="text" name="weight" value="<?= isset($values[0]->weight) ? $values[0]->weight : "" ?>">
		</td>
		<td>
			Используется для калькулятора и для выдачи клиенту
		</td>
	    </tr>

		<tr><th scope="row">Объем (м3)</th>
		<td>		
		<input type="text" name="volume" value="<?= isset($values[0]->volume) ? $values[0]->volume : "" ?>">
		</td>
		<td>
			Используется для калькулятора и для выдачи клиенту
		</td>
	    </tr>

		<tr><th scope="row">Цена за километр (руб)</th>
		<td>		
		<input type="text" name="price" value="<?= isset($values[0]->price) ? $values[0]->price : ""  ?>">
		</td>
		<td>
			Используется для калькулятора
		</td>
	    </tr>

	    <tr><th scope="row">Сообщение пользователю</th>
		<td>		
		<input type="text" name="msg" value="<?= isset($values[0]->msg) ? $values[0]->msg : "" ?>">
		</td>
		<td>
			Выводится в заголовке контактной формы
		</td>
	    </tr>
</tbody>
</table>







<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="Сохранить изменения">
</p>
</form>