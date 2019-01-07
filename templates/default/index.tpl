{{ include("templates/default/header.tpl") }}
<div class="container">
<table class="table table-hover">
						<thead>
							<tr>
								<th>{{$lang_title}}</th>
								<th>{{$lang_size}}</th>
								<th>{{$lang_leechers}}</th>
								<th>{{$lang_seeders}}</th>
								<th>{{$lang_peers}}</th>
							</tr>
</thead>
<tbody>
{{ BEGIN torrents }}<tr>
<td><a href="browse.php?tid={{$tid }}&le={{ $leechers }}&se={{ $seeders }}">{{ $title }}</a> 
<br /><span class="meta">{{$lang_category}}: <span class="label">{{ $category }}</span></span></td>
<td>{{ $size }}</td>
<td>{{ $leechers }}</td>
<td>{{ $seeders }}</td>
<td><span class="label label-important">{{ $peers }}</span></td>
</tr>{{ END }} 
</tbody>
</table>



        {{ BEGIN pagination }} All: {{ $all}} <br>
{{ $pages}}
        {{ END pagination }}

</div>
{{ include("templates/default/footer.tpl") }}