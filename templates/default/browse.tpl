{{ include("templates/default/header.tpl") }}
<div class="container">
<div class="page-header">{{ IF $torrents }}
<div class="row-fluid">
    <div class="span10">
      <h4>{{ BEGIN torrents }}{{ $title }}</h4></div>
<div class="span1">
 <div class="btn-group pull-right">
              <a href="#" data-toggle="dropdown" class="btn btn-primary dropdown-toggle">
                  <i class="icon-white icon-plus"></i>
                  {{$lang_download}}
                  <span class="caret"></span>
                </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="/browse.php?tid={{$tid}}&act=download"><i class="icon-file"></i>{{$lang_download_torrent}}</a>
                </li>
                <li>
                  <a href="{{ BEGIN magnet }}{{$magnet}}{{ END }}">
                    <i class="icon-magnet"></i>
                    {{$lang_download_magnet}}
                  </a>
                </li>
                <li class="divider"></li>
<li><a  rel="nofollow" href="/report.php?tid={{$tid}}"><i class="icon-flag"></i>{{$lang_report}}</a></li>
              </ul>
            </div> </div>
 </div></div>
 
{{ IF $result }}
<div class="alert alert-info">
        <a class="close" data-dismiss="alert" href="#">x</a>
        <h4 class="alert-heading">Information</h4>
{{BEGIN  errors }}{{ $result }}</div>{{END  errors }}
{{ END if-result }}
<div class="tabbable tabs-left">
              <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#rA">{{$lang_description}}</a></li>
                <li class=""><a data-toggle="tab" href="#rB">{{$lang_files}}</a></li>
                <li class=""><a data-toggle="tab" href="#rC">{{$lang_etc}}</a></li>
				<li class=""><a data-toggle="tab" href="#rD">{{$lang_comment}}</a></li>
              </ul>
              <div class="tab-content">
                <div id="rA" class="tab-pane active">

<div class="row-fluid">
    <div class="span8">
{{ $description }}</div>
    <div class="span4">
    <!--Body content-->
<img src="/data/timg/{{$tor_hash}}.jpg" class="img-rounded">

    <ul class="nav nav-list"> 
<li class="divider"></li>
<i class="icon-hdd"></i>{{$lang_size}}: <span class="badge">{{ $size }}</span>

        <a class="btn btn-mini btn-danger" title="Report" rel="nofollow" href="/report.php?tid={{$tid}}">
      <i class="icon-flag"></i>{{$lang_report_short}}
    </a>    


<li class="divider"></li>
<i class="icon-download"></i>{{$lang_leechers}}: <span class="badge">{{ $leechers }}</span>
<li class="divider"></li>
<i class="icon-upload"></i>{{$lang_seeders}}: <span class="badge">{{ $seeders }}</span>
<li class="divider"></li>
<i class="icon-resize-vertical"></i>{{$lang_peers}}: <span class="badge">{{ $peers }}</span>
<li class="divider"></li>
<i class="icon-time"></i>{{$lang_add_date}}: <span class="badge">{{ date("%d-%m-%Y (%H:%M)",$addtime); }}</span>
<li class="divider"></li>
<i class="icon-eye-open"></i>{{$lang_viewers}}: <span class="badge">{{ $clickcount}}</span>
<li class="divider"></li>
<i class="icon-barcode"></i><small>{{ $tor_hash }}</small>
<li class="divider"></li>

<div id="akintor"><center><a class="btn-mini btn btn-info" id="driver"><i class="icon-hand-right"></i>{{$lang_show_akin}} <i class="icon-hand-left"></i></a></center></div>
    </ul>
{{ END }}
    </div> </div>

                </div>
                <div id="rB" class="tab-pane"><table class="table table-striped table-condensed"><thead>
            <tr>
              <th>#</th>
              <th>{{$lang_title}}</th>
              <th>{{$lang_size}}</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            {{ BEGIN file_list }}<tr>
              <td>{{ $count }}.</td>
              <td>{{ $name }}</td>
              <td>{{ $length }}</td>
              </tr>{{ END }}
          </tbody>
        </table>
                </div>
                <div id="rC" class="tab-pane">
<div class="row-fluid">
  <div class="span7">
<table class="table table-striped table-condensed"><thead>
            <tr>
              <th>#</th>
              <th>{{$lang_tracker}}</th>
            </tr>
          </thead>
          <tbody>
            {{ BEGIN tracker }}<tr>
              <td>{{ $countt }}.</td>
              <td>{{ $tracker }}</td>
            </tr>{{ END }}
          </tbody>
        </table></div>




    {{ BEGIN metadata }}     
  <div class="span5"><ul class="nav nav-list"> 
<li class="divider"></li>
<i class="icon-time"></i>{{$lang_creation_date}}: <span class="badge">{{ date("%d-%m-%Y (%H:%M)",$date); }}</span>
<li class="divider"></li>
<i class="icon-comment"></i>{{$lang_comment}}: <span class="badge">{{ $comment }}</span>
<li class="divider"></li>
<i class="icon-cog"></i>{{$lang_created}}: <span class="badge">{{ $created }}</span>
<li class="divider"></li>
<i class="icon-hdd"></i>{{$lang_length}}: <span class="badge">{{ $length }}</span>
<li class="divider"></li>
    </ul></div>{{ END }}
</div></div>




<div id="rD" class="tab-pane">
<div class="row-fluid">
  <div class="span7">
<script type="text/javascript">
        $(document).ready(function(){
             $("#randomdiv").load("ajax/akintor.php");

        });
</script>
   <script type="text/javascript" language="javascript">
  $(document).ready(function() {
      $("#driver").click(function(event){
          $.get( 
             "/ajax/akintor.php",
             { cat: "{{$title}}" },
             function(data) {
                $('#akintor').html(data);
             }

          );
      });
   });
   </script>

<div id="randomdiv"></div>

  
  <script src="/templates/static/js/jquery.html5form-1.5-min.js"></script>

  {{ BEGIN fileinfo }}
{{ $leechers }}
{{ $seeders }}
{{ $peers }}
{{ END }}
  
  </div>
</div></div>






              </div>
            </div>

  </div>
{{ ELSE }}
No post data yet!
{{ END if-title }}
{{ include("templates/default/footer.tpl") }}