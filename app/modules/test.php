<style>
body{
    background: url('/public/background.jpg');
}
</style>
<div class="row justify-content-center">
<div class="col">
<div id="ytWidget"></div><script src="https://translate.yandex.net/website-widget/v1/widget.js?widgetId=ytWidget&pageLang=en&widgetTheme=light&autoMode=false" type="text/javascript"></script>
</div>

</div>
<script  site_id='1'  detect-host='http://s271548.smrtp.ru/scan' src='http://s271548.smrtp.ru/public/js/connect_prod.js' code='a97d327c6206ad17d6b1982171b9ca26'></script>
<div class="row p-5 m-5 justify-content-center align-items-center" style='background:yellowgreen;border-radius:2%'>
<h2 class='text-center p-5'>Тестируйте Иньекции</h2>
<div class="col-12  rouned " >

    <form action="" method='post'>
        <div class="form-group">
            <label for="injection">Запрос</label>
            <input type="text" id='injection' name='text' class='form-control'>
        </div>
        <div class="form-group">
    <label for="ugrozy">Выберите угрозу из списка</label>
    <select class="form-control" id="ugrozy" onchange="selectUgroz(this.value)" >
    <option value="">Выбрать</option>
    <option value="<script>alert('xss')</script>">XXS</option>
    <option value="<script>request.send(data);</script>">CSRF</option>
    <option value="<script>php?page=expect://ls</script>">LFI</option>
    <option value="<script>.submit(R)</script>">RFE</option>
    <option value="<select>login + ‘-‘</select>">SQLI</option>
    <option value='<script>>>./stack "123 %x"</script>'>Format-string</option>
    <option value="<script>$name = $_GET['name'];</script>">Dos</option>
    <option value="<select>* FROM news WHERE id_news = -1 OR 1=1</select>">Id</option>


    </select>

</div>

        <br>
        <input type="submit" class='btn btn-primary text-center' value='Проверить'>
    </form>
</div>
<div class="row pt-5 mt-5">
    <div id="res" class="alert alert-info"></div>
</div>

</div>

<script>
function selectUgroz(params){
document.getElementById('injection').value=params;

}

</script>