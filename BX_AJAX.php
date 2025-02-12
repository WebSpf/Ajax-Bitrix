<?
//подключаем пролог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
//устанавливаем заголовок страницы
$APPLICATION->SetTitle("AJAX");

// Подключаем ядро Bitrix для работы с AJAX
   CJSCore::Init(array('ajax'));
   $sidAjax = 'testAjax';

// Проверяем, является ли запрос AJAX-запросом   
if(isset($_REQUEST['ajax_form']) && $_REQUEST['ajax_form'] == $sidAjax){
   $GLOBALS['APPLICATION']->RestartBuffer();
   echo CUtil::PhpToJSObject(array(
            'RESULT' => 'HELLO',
            'ERROR' => ''
   ));
   die();
}

?>
<!-- Выводим HTML-->
<div class="group">
   <div id="block"></div >
<!-- Выводим надпись, которую видно на странице до загрузки RESULT-->   
   <div id="process">wait ... </div >
</div>
<script>
   window.BXDEBUG = true;

// Загружаем данные через AJAX   
function DEMOLoad(){
   BX.hide(BX("block"));
   BX.show(BX("process"));
// Передаем текущий URL
   BX.ajax.loadJSON(
      '<?=$APPLICATION->GetCurPage()?>?ajax_form=<?=$sidAjax?>',
      DEMOResponse
   );
}

/* Обработываем ответ AJAX
выводим данные в консоль для отладки
показываем полученный результат
*/
function DEMOResponse (data){
   BX.debug('AJAX-DEMOResponse ', data);
   BX("block").innerHTML = data.RESULT;
   BX.show(BX("block"));
   BX.hide(BX("process"));

   BX.onCustomEvent(
      BX(BX("block")),
      'DEMOUpdate'
   );
}
// Выполняем код после загрузки страницы
BX.ready(function(){
   /*
   BX.addCustomEvent(BX("block"), 'DEMOUpdate', function(){
      window.location.href = window.location.href;
   });
   */
   BX.hide(BX("block"));
   BX.hide(BX("process"));

   //Привязываем обработчик события клик
    BX.bindDelegate(
      document.body, 'click', {className: 'css_ajax' },
      function(e){
         if(!e)
            e = window.event;
         
         DEMOLoad();
         return BX.PreventDefault(e);
      }
   );
   
});

</script>
<!-- Выводим HTML-->
<div class="css_ajax">click Me</div>
<?
//подключаем эпилог ядра bitrix
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>
