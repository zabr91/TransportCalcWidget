
<a href = "?page=<?= $_GET['page'] ?>">Назад</a>
<form action="" method="POST">

	<input type="text" name="id" hidden value="<?= isset($values[0]->id) ? $values[0]->id : ""?>">

<p><label>
	Title
	<input type="text" name="title" value="<?= isset($values[0]->title) ? $values[0]->title :  "" ?>">
</label></p>
<p><label>
	Description
	<input type="text" name="description" value="<?= isset($values[0]->description) ? $values[0]->description : "" ?>">
</label></p>
<p><label>
	Distanse
	<input type="text" name="distance" value="<?= isset($values[0]->distance) ? $values[0]->distance : "" ?>">
</label></p>
<p><label>
	Weight
	<input type="text" name="weight" value="<?= isset($values[0]->weight) ? $values[0]->weight : "" ?>">
</label></p>
<p><label>
	Volume
	<input type="text" name="volume" value="<?= isset($values[0]->volume) ? $values[0]->volume : "" ?>">
</label></p>
<p><label>
	Price
	<input type="text" name="price" value="<?= isset($values[0]->price) ? $values[0]->price : ""  ?>">
</label></p>
<p><label>
	Message
	<input type="text" name="msg" value="<?= isset($values[0]->msg) ? $values[0]->msg : "" ?>">
</label></p>

<p class="submit">
	<input type="submit" name="submit" id="submit" class="button button-primary" value="Сохранить изменения">
</p>
</form>