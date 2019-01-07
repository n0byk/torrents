{{ include("templates/default/header.tpl") }}
<div class="main-container">
<div class="container-fluid">
			<section>
				<div class="page-header">
				<h1>{{ IF $title }}
        {{ BEGIN title }} {{ $title }} {{ END title }}
	</h1>
				</div>
				<div class="row-fluid">

					<div class="alert alert-info">
						<button data-dismiss="alert" class="close">x</button>
						<strong>INFO!</strong> This is activation page gust push the button and activate this torrent!
						</div>
				</div>
	
<center>
            <form class="form-horizontal" method="POST" enctype="multipart/form-data" action="activate.php"><fieldset>
              <fieldset>
{{ begin token }}
<input type="hidden" name="token" value="{{ $token }}">
<input type="hidden" name="activatecode" value="{{ $activate }}">
{{ end }}
<input type="submit" name="activate" class="btn btn-large btn-success" value="Activate this torrent"></input>
              </fieldset>
            </form>

</center>


				</div>
			</section>
{{ ELSE }}
The torrent not faund pleas try again. Or olrady activate
{{ END if-title }}
</div>
</body></html>