
var scripts=document.querySelectorAll('script');
var site_id=null;
var code=null;
var detecthost=null;
for(var it=0; it<=scripts.length;it++){
    if (scripts[it]!=undefined) {
        if (scripts[it].attributes['site_id']!=undefined&& scripts[it].attributes['code']!=undefined&& scripts[it].attributes['detect-host']!=undefined) {
            site_id=scripts[it].attributes['site_id'].value;
            code=scripts[it].attributes['code'].value;
            detecthost=scripts[it].attributes['detect-host'].value;
        }   
    }
}
if (site_id!=null&&code!=null&&detecthost!=null) {
    
    document.addEventListener("DOMContentLoaded", function() {
        window.onload = function(){ 
            console.log('d:',detecthost);
            if ($!==undefined) {
                $('form').on('submit', function(e){
                    e.preventDefault();
                    dt=$(this).serialize()+'&location='+location.href+'&code='+code+'&site_id='+site_id;
                    $.post(detecthost, dt,function( data ) {
                      
                        console.log(data);
                        
                        var txt=`<ol>`;
                        for (const iterator of data.result) {
                            txt+=`<li> ${iterator.title}<br>
                            Угрозы: 
                            <ul>`;
                            iterator.detected.forEach(element => {
                                txt+=`<li>${element}</li>`;
                            });
                            txt+=`</ul></li>`
                        }
                        txt+='</ol>';
                        $('#res').html(txt);
                    });
                });   
            }
        };        
    });

}else
    console.error('Не верный скрипт');