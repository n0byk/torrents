{{ BEGIN akintor }}
<a href="./browse.php?tid={{$tid }}">{{ $title }}</a>
<br /> <p class="muted"><i class="icon-eye-open"></i>{{ $clickcount}}  <i class="icon-hdd"></i>{{$size}}  <i class="icon-time"></i>{{ date("%d-%m-%Y (%H:%M)",$tmp_addtime); }}</p>
<li class="divider"></li>
{{ END akintor }}