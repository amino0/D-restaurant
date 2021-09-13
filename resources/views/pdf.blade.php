
    @foreach ($article as $row)
<center> <i><b><h4>
</h4></b></i>
    <img src="data:image/png;base64,{!! $qrcode !!}" width="400" height="400">
   <h1><b>{{$row->id}} DJ/ 2021 </b></h1>
</center>
@endforeach
