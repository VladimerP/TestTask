<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<div class='block-control'>
  <div>
    <a href="#" id='arrow-left' class="arrow-left">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
      </svg>
    </a>
    <span id='current-month'class='current-month'><?=date('m.Y')?></span>
    <a href="#" id='arrow-right' class="arrow-right">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
        <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
      </svg>
    </a>
  </div>
  <button id="addNote" class='add-note' type="button">Добавить заметку</button>
</div>
<div id="formModal" class="modal" data-id='<?=$arParams['ID_IBLOCK']?>'>
    <div class="modal-content">
        <span id="close-modal"class="close">&times;</span>
        <form id="myForm">
			<label for="DATE_EVENT">Дата:</label>
			<input type="date" id="DATE_EVENT" name="DATE_EVENT" required>
			
			<label for="TITLE_EVENT">Название:</label>
			<input type="text" id="TITLE_EVENT" name="TITLE_EVENT" required>
			
			<label for="DETAIL_EVENT">Описание:</label>
			<textarea id="DETAIL_EVENT" rows="5" name="DETAIL_EVENT" required></textarea>
			
			<input type="submit" id='saveNote' value="Сохранить">
		</form>
    </div>
</div>

<div id="formModalInfo" class="modal">
    <div class="modal-content">
        <span id="close-modalInfo"class="close">&times;</span>
		<label>Дата:</label>
		<p id='dateNote' class='detailNote'>None</p>
		<label>Название:</label>
        <h3 id='titleNote' class='titleNote'>None</h3>
		<label>Описание:</label>
		<p id='detailNote' class='detailNote'></p>
    </div>
</div>

<div class='block-claendar'>
	<table class="calendar-test">
	<thead>
		<tr class='head-custom-calendar'>
			<th>Пн</th>
			<th>Вт</th>
			<th>Ср</th>
			<th>Чт</th>
			<th>Пт</th>
			<th>Сб</th>
			<th>Вс</th>
		</tr>
	</thead>
	<tbody id='tbody-custom-calendar' class='tbody-custom-calendar'>
		<?
		$emptyCells = $arResult['FIRST_DAY_WEEK'] - 1;
		if($emptyCells>0) {
			?><tr class='body-custom-calendar'><?
		}
		for ($i = 0; $i < $emptyCells; $i++) {
			?><td></td><?
		}
		$KEYS;
		for ($day = 1; $day <= $arResult['DAY_IN_MONTHS']; $day++) {
			if (($day + $emptyCells) % 7 == 1) {
				?></tr><tr class='body-custom-calendar'><?
			}
			$key = $day.'.'.$arResult['DATE_CHECK'];
			$dateTimestamp = strtotime($key);
			$formattedDate = date('d.m.Y', $dateTimestamp);
			$dayOff= '';
			if($formattedDate == date('d.m.Y')) {
				$dayOff="today";
			}
			?><td class='<?=$dayOff?>'>
				<div class='content-note-calendar'>
					<?
						if($arResult['EVENT'][$formattedDate]){
							foreach($arResult['EVENT'][$formattedDate] as $event){
								?><a class='note-calendar' id='<?=$event['ID']?>'><?=$event['PROPERTY_TITLE_EVENT_VALUE']?></a><?
							}
						}
					?>
				</div>
				<p class='day-calendar'><?=$day?></p>
			</td><?

			if (($day + $emptyCells) % 7 == 0 || $day == $arResult['DAY_IN_MONTHS']) {
				?></tr><?
			}
		}
		?>
	</tbody>
	</table>
</div>
