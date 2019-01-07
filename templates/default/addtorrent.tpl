{{ include("templates/default/header.tpl") }}
    <link href="/templates/static/js/redactor/bootstrap-wysihtml5.css" rel="stylesheet">

<div class="container">

{{ IF $result }}
<div class="alert alert-info">
        <a class="close" data-dismiss="alert" href="#">x</a>
        <h4 class="alert-heading">Information</h4>
{{BEGIN  errors }}{{ $result }}</div>{{END  errors }}
{{ END if-result }}


            <div class="page-header">
              <h2>{{$lang_addtorrent}}</h2>
            </div>
          
            <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="addtorrent.php" id="select_form">
            <fieldset>
<div class="control-group">
                    <label class="control-label">{{$lang_torrent}}</label>
                    <div class="controls">              
<input type="file"  name="torrent" class="input-xxlarge" multiple="multiple" accept="application/x-bittorrent, application/bittorrent"></input>
                    </div>
</div>
<div class="control-group">
                    <label class="control-label">{{$lang_torrentimg}}</label>
                    <div class="controls">              
<input type="file"  name="torrentimg" class="input-xxlarge" multiple="multiple" accept="application/x-bittorrent, application/bittorrent"></input>
                    </div>
</div>
<div class="control-group">
                    <label class="control-label">{{$lang_tname}}</label>
                    <div class="controls">
                      <input type="text" name="tname" placeholder="{{$lang_tname}}" class="input-xxlarge"></input>
                    </div>
</div>
<div class="control-group">
                    <label class="control-label">{{$lang_email}}</label>
                    <div class="controls">
                      <input type="text" name="email" placeholder="{{$lang_email}}" class="input-xxlarge"></input>
                    </div>
</div>
<div class="control-group">
                    <label class="control-label">{{$lang_category}}</label>
                    <div class="controls">
 <select id="category" name="category" class="input-xlarge" onchange="loadCity(this)">
 <option>choose...</option> 
 {{ begin category }}<option value="{{$id_cat}}">{{$name}}</option>{{ end }}
</select>
        <select name="subcategory" disabled="disabled" class="input-xlarge">
            <option>choose...</option>
        </select>
<p class="help-block">{{$lang_select_category}}</p>

                    </div>
</div>
<div class="control-group">
                    <label class="control-label">{{$lang_description}}</label>
                    <div class="controls">
                        <textarea class="input-xxlarge" id="description" rows="5" name="description" placeholder="Enter text ..."></textarea>
                                          </div>
</div>
<div class="control-group">
                    <label class="control-label">{{$lang_captcha}}</label>
                    <div class="controls">{{ begin captcha }}{{ $captcha }}{{ end }}</div>
</div>
<input type="hidden" name="token" value="{{ begin token }}{{ $token }}{{ end }}">
<div class="control-group">
                        <label for="optionsCheckbox" class="control-label">{{$lang_rules}}</label>
                        <div class="controls">
                          <label class="checkbox">
                            <input type="checkbox" name="rules" value="1" id="optionsCheckbox"></input>
                            {{$lang_rules_ok}}
                          </label>
                        </div></div>
<div class="form-actions">
<input type="submit" name="addtorrent" class="btn btn-primary" value="{{$lang_submit}}"></input>
<input type="reset" class="btn" value="{{$lang_reset}}"></input>
</div>
            </fieldset>
</form>

          </div>
		  
		  <!--==wysi==-->
<script src="/templates/static/js/redactor/bootstrap-wysihtml5.js"></script>
<script src="/templates/static/js/redactor/wysihtml5-0.3.0.min.js"></script>  
<script type="text/javascript">
	$('#description').wysihtml5();

</script>




<script type="text/javascript">
    // <![CDATA[
        function loadCity(select)
        {
            var citySelect = $('select[name="subcategory"]');
            citySelect.attr('disabled', 'disabled'); // делаем список городов не активным
            
            // послыаем AJAX запрос, который вернёт список городов для выбранной области
            $.getJSON('addtorrent.php', {action:'getCity', region:select.value}, function(cityList){
                
                citySelect.html(''); // очищаем список городов
                
                // заполняем список городов новыми пришедшими данными
                $.each(cityList, function(i){
                    citySelect.append('<option value="' + this + '">' + this + '</option>');
                });
                
                citySelect.removeAttr('disabled'); // делаем список городов активным
                
            });
        }
    // ]]>
</script>
 {{ include("templates/default/footer.tpl") }}