{{ include("templates/default/header.tpl") }}
<div class="main-container">
<div class="container-fluid">
            <section>
                <div class="page-header">
                <h1>{{ IF $title }}
        {{ BEGIN tortitle }} <a href="browse.php?tid={{$tid}}">{{ $title }}</a>
    </h1>
                </div>
                <div class="row-fluid">

                    <div class="alert alert-info">
                        <button data-dismiss="alert" class="close">x</button>
                        <strong>INFO!</strong> {{$lang_report_info}}
                        </div>
                </div>
    
            <div class="page-header">
              <h2>{{$lang_report}}</h2>
            </div>
          
            <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="report.php" id="select_form">
            <fieldset>
<div class="control-group">
                    <label class="control-label">{{$lang_title}}</label>
                    <div class="controls">
                        <span class="input-xxlarge uneditable-input">{{ $title }}</span><span class="help-inline"><a href="browse.php?tid={{$tid}}" target="_blank">{{$lang_link}}</a></span>
                    </div>
</div>{{ END tortitle }}
<div class="control-group">
<label class="control-label">{{$lang_reason}}</label>
                    <div class="controls">
<label class="radio">
<input type="radio" name="reason" id="1" value="copyright" checked>
Copyright Infringement
</label>
<label class="radio">
<input type="radio" name="reason" id="2" value="invalid_data">
Invalid data or Rules violation
</label>
<label class="radio">
<input type="radio" name="reason" id="3" value="other">
Duplicate torrent or Other reason
</label>
</div>
</div>


<div class="control-group">
                    <label class="control-label">{{$lang_description}}</label>
                    <div class="controls">
                        <textarea id="textarea" class="input-xxlarge" rows="5" name="description"></textarea>
                                          </div>
</div>
<input type="hidden" name="token" value="{{ begin token }}{{$token}}{{ end }}">
<div class="form-actions">
<input type="submit" name="report" class="btn btn-primary" value="{{$lang_report}}"></input>
<input type="reset" class="btn" value="{{$lang_reset}}"></input>
</div>
            </fieldset>
</form>

          </div>


                </div>
            </section>
{{ ELSE }}
The torrent not faund pleas try again.
{{ END if-title }}

</div>
 {{ include("templates/default/footer.tpl") }}